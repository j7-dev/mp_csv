<?php


/**
 * @wordpress-plugin
 * Plugin Name:       MP CSV
 * Plugin URI:
 * Description:       將CSV檔上傳到指定地方，自動抓取資料，並且提供短碼做業績排行顯示
 * Version:           0.0.3
 * Author:            j7.dev.gg
 * Author URI:        https://github.com/j7-dev
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       mp_csv
 * WC requires at least: 5
 * WC tested up to: 5.6.0
 */

declare(strict_types=1);

namespace MorePower\CSV;

require_once __DIR__ . '/inc/class-components.php';

class mp_csv
{
	private $filename = 'wp_live_demo.csv'; // CSV 檔案名稱
	private $uploadDir = WP_CONTENT_DIR . '/demo/'; // 檔案上傳路徑
	private $avatarImagesUrl;
	private $csvFilePath;
	private $csvData;
	private $pluginUrl;
	private $shortcode = 'csv_table'; // 短碼名稱
	private $count_cols = 8; // 要取幾欄的資料





	function __construct()
	{
		$this->init();

		\add_action('wp_enqueue_scripts', [$this, 'components_assets']);
		\add_shortcode($this->shortcode, [$this, 'callback']);
	}

	private function init(): void
	{
		$this->pluginUrl = \plugin_dir_url(__FILE__);
		$this->avatarImagesUrl = \content_url('/demo/images');

		$this->csvFilePath = $this->uploadDir . $this->filename;


		// 開啟CSV檔案
		$file = fopen($this->csvFilePath, 'r');

		// 檢查檔案是否成功開啟
		if ($file) {
			// 初始化一個空陣列，用於存儲每一行(row)的數據
			$data = [];

			// 逐行讀取CSV檔案
			while (($row = fgetcsv($file)) !== false) {
				// 將每一行(row)的數據添加到$data陣列中
				$data[] = $row;
			}

			// 關閉檔案
			fclose($file);

			$csvData = [];
			for ($i = 0; $i < $this->count_cols; $i++) {
				$alphabet = chr(65 + $i);
				$csvData[$alphabet] = [];
			}

			$asciiA = 65;

			foreach ($data as $key => $value) {
				if (!in_array($key, [0, 1])) {
					for ($i = 0; $i < $this->count_cols; $i++) {
						if (!empty($value[$i])) {
							$alphabet = chr($asciiA + $i);
							array_push($csvData[$alphabet], $value[$i] ?? '');
						}
					}
				}
			}

			$this->csvData = $csvData;
		} else {
			// 處理檔案無法開啟的情況
			echo "無法開啟檔案。";
		}
	}

	public function components_assets(): void
	{
		\wp_enqueue_style('mp_csv', $this->pluginUrl . '/src/assets/scss/index.css', array(), '0.1.0', 'all');
	}

	public function callback($atts = array()): string
	{
		extract(\shortcode_atts(array(
			'column' => 'A',
			'title' => '當週竄升 TOP 50'
		), $atts));

		$column = strtoupper($column);

		$tableData = $this->csvData[$column] ?? [];


		// 使用 array_slice() 切割前5個元素
		$topFive = array_slice($tableData, 0, 5);

		// 使用 array_slice() 切割剩餘的元素
		$rest = array_slice($tableData, 5);

		// 計算陣列的大小
		$arraySize = count($rest);

		// 計算分割點，這裡使用ceil函數確保分割點向上取整，以確保兩個分割後的陣列大小盡量相等
		$splitPoint = ceil($arraySize / 2);


		// 使用array_chunk分割陣列
		$splitArrays = array_chunk($rest, (int)$splitPoint);

		// 分割後的兩個陣列
		$firstArray = $splitArrays[0];
		$secondArray = $splitArrays[1];



		$html = '';

		function getAvatarProps(int $key, string $name, string $column, string $url): array
		{

			$props = [
				'title' => $name,
				'src'   => $url . '/' . $column . (((int) $key) + 1) . '.png',
			];
			if ($key === 0) {

				return array_merge($props, [
					'class' => 'text-left crown',
				]);
			}
			if ($key === 2) {
				return array_merge($props, [
					'class' => 'text-right',
				]);
			}

			return $props;
		}

?>


		<h2 class="text-center text-xl mb-12 font-bold"><?= $title ?></h2>

		<div class="grid grid-cols-6 gap-y-8">
			<?php foreach ($topFive as $key => $name) :
				$args = getAvatarProps($key, $name, $column, $this->avatarImagesUrl);
			?>
				<div class="<?= $key >= 3 ? 'col-span-3' : 'col-span-2' ?>">
					<?= Components::renderAvatar($args); ?>
				</div>
			<?php endforeach; ?>
		</div>


		<div class="grid grid-cols-1 md:grid-cols-2 md:gap-x-4">
			<table class="mt-12 table table-vertical">
				<thead>
					<tr>
						<th>排名</th>
						<th>名稱</th>
					</tr>
				</thead>
				<tbody>
					<?php foreach ($firstArray as $key => $name) : ?>
						<tr>
							<td><?= $key + 6 ?></td>
							<td><?= $name ?></td>
						</tr>
					<?php endforeach; ?>
				</tbody>
			</table>

			<table class="mt-12 table table-vertical">
				<thead>
					<tr>
						<th>排名</th>
						<th>名稱</th>
					</tr>
				</thead>
				<tbody>
					<?php foreach ($secondArray as $key => $name) : ?>
						<tr>
							<td><?= $key + $splitPoint + 6 ?></td>
							<td><?= $name ?></td>
						</tr>
					<?php endforeach; ?>
				</tbody>
			</table>
		</div>


<?php
		$html .= ob_get_clean();


		return $html;
	}
}

new mp_csv();
