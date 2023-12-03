<?php


/**
 * @wordpress-plugin
 * Plugin Name:       MP CSV
 * Plugin URI:
 * Description:       將CSV檔上傳到指定地方，自動抓取資料，並且提供短碼做業績排行顯示
 * Version:           0.0.6
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
	private $top5_shortcode = 'csv_table_top5'; // 短碼名稱

	private $count_cols = 8; // 要取幾欄的資料





	function __construct()
	{
		$this->init();

		\add_action('wp_enqueue_scripts', [$this, 'components_assets']);
		\add_shortcode($this->shortcode, [$this, 'csv_table_callback']);
		\add_shortcode($this->top5_shortcode, [$this, 'csv_table_top5_callback']);

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

	public function csv_table_callback($atts = array()): string
	{
		extract(
			\shortcode_atts(
				array(
					'column' => 'A',
					'title' => '當週竄升 TOP 50'
				),
				$atts
			)
		);

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
		$splitArrays = array_chunk($rest, (int) $splitPoint);

		// 分割後的兩個陣列
		$firstArray = $splitArrays[0];
		$secondArray = $splitArrays[1];



		$html = '';
		$html .= $this->csv_table_top5_callback($atts);
		ob_start();
		?>

		<div class="grid grid-cols-1 md:grid-cols-2 border-4 border-solid border-black">
			<div class="border-transparent md:border-gray-300"
				style="border-right-style: solid; border-right-width:0.25rem">
				<table class="mt-12 mb-0 table table-vertical">

					<tbody>
						<?php foreach ($firstArray as $key => $name):
							$index = $key + 6;
							?>
							<tr>
								<td>
									<?= sprintf('%02d', $index) ?>
								</td>
								<td>
									<?= $name ?>
								</td>
							</tr>
						<?php endforeach; ?>
					</tbody>
				</table>
			</div>
			<div>
				<table class="md:mt-12 mb-0 table table-vertical">

					<tbody>
						<?php foreach ($secondArray as $key => $name):
							$index = $key + $splitPoint + 6;
							?>
							<tr>
								<td>
									<?= sprintf('%02d', $index) ?>
								</td>
								<td>
									<?= $name ?>
								</td>
							</tr>
						<?php endforeach; ?>
					</tbody>
				</table>
			</div>
		</div>


		<?php
		$html .= ob_get_clean();


		return $html;
	}

	public function csv_table_top5_callback($atts = array()): string
	{
		extract(
			\shortcode_atts(
				array(
					'column' => 'A',
					'title' => '當週竄升 TOP 50'
				),
				$atts
			)
		);

		$column = strtoupper($column);

		$tableData = $this->csvData[$column] ?? [];

		// 使用 array_slice() 切割前5個元素
		$topFive = array_slice($tableData, 0, 5);

		$html = '';
		ob_start();
		?>
		<?php if ($title): ?>
			<h2 class="text-center text-xl mb-12 font-bold">
				<?= $title ?>
			</h2>
		<?php endif; ?>

		<div class="text-center pl-8 md:pl-0">
			<?php foreach ($topFive as $key => $name):
				$args = $this->getAvatarProps($key, $name, $column, $this->avatarImagesUrl);
				?>
				<div class="block w-full my-8 md:w-[32%]  md:inline-block">
					<?= Components::renderAvatar($args); ?>
				</div>
			<?php endforeach; ?>
		</div>

		<?php
		$html .= ob_get_clean();


		return $html;
	}



	public function getAvatarProps(int $key, string $name, string $column, string $url): array
	{

		$props = [
			'index' => $key,
			'column' => $column,
			'title' => $name,
			'src' => $url . '/' . $column . (((int) $key) + 1) . '.png',
			'class' => 'mx-2'
		];


		return $props;
	}
}

new mp_csv();
