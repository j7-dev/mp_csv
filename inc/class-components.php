<?php

declare(strict_types=1);

namespace MorePower\CSV;



class Components
{
	static public function renderAvatar(array $props): string
	{

		extract(
			\shortcode_atts(
				array(
					'index' => 1,
					'column' => 'A',
					'src' => '',
					'title' => '',
					'size' => 'h-16 w-16 md:h-24 md:w-24 ',
					'class' => 'text-center'
				),
				$props
			)
		);

		//$src = http://test.local/wp-content/demo/images/B2.png

		$file_path = \wp_normalize_path(str_replace(\content_url(), WP_CONTENT_DIR, $src));



		if (!file_exists($file_path) || empty($src)) {
			$src = 'data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0iVVRGLTgiPz4KPHN2ZyB2aWV3Qm94PSIwIDAgMTI4IDEyOCIgdmVyc2lvbj0iMS4xIiB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHJvbGU9ImltZyIgYXJpYS1sYWJlbD0ieHhsYXJnZSI+CiAgICA8Zz4KICAgICAgICA8Y2lyY2xlIGN4PSI2NCIgY3k9IjY0IiByPSI2NCIgZmlsbD0iIzg5OTNhNCIgLz4KICAgICAgICA8Zz4KICAgICAgICAgICAgPHBhdGggZmlsbD0iI2ZmZiIKICAgICAgICAgICAgICAgIGQ9Ik0xMDMsMTAyLjEzODggQzkzLjA5NCwxMTEuOTIgNzkuMzUwNCwxMTggNjQuMTYzOCwxMTggQzQ4LjgwNTYsMTE4IDM0LjkyOTQsMTExLjc2OCAyNSwxMDEuNzg5MiBMMjUsOTUuMiBDMjUsODYuODA5NiAzMS45ODEsODAgNDAuNiw4MCBMODcuNCw4MCBDOTYuMDE5LDgwIDEwMyw4Ni44MDk2IDEwMyw5NS4yIEwxMDMsMTAyLjEzODggWiIgLz4KICAgICAgICAgICAgPHBhdGggZmlsbD0iI2ZmZiIKICAgICAgICAgICAgICAgIGQ9Ik02My45OTYxNjQ3LDI0IEM1MS4yOTM4MTM2LDI0IDQxLDM0LjI5MzgxMzYgNDEsNDYuOTk2MTY0NyBDNDEsNTkuNzA2MTg2NCA1MS4yOTM4MTM2LDcwIDYzLjk5NjE2NDcsNzAgQzc2LjY5ODUxNTksNzAgODcsNTkuNzA2MTg2NCA4Nyw0Ni45OTYxNjQ3IEM4NywzNC4yOTM4MTM2IDc2LjY5ODUxNTksMjQgNjMuOTk2MTY0NywyNCIgLz4KICAgICAgICA8L2c+CiAgICA8L2c+Cjwvc3ZnPgo=';
		}

		ob_start();
		?>
		<div class="avatar flex items-center <?= $class ?>">
			<div
				class="group inline-block rounded-full ring ring-primary ring-offset-base-100 ring-offset-2 <?= $size; ?>">
				<img class="group-hover:scale-[120%] duration-300 rounded-full object-cover <?= $size; ?>"
					src="<?= $src ?>" />
			</div>
			<?php if ($title): ?>
				<h3 class="font-semibold text-base md:text-lg flex items-center ml-3">
					<span class="text-[1.5rem] mr-2">
						<?= sprintf('%02d', $index + 1) ?>
					</span>
					<?= $title ?>
				</h3>
			<?php endif; ?>
		</div>

		<?php
		$html = ob_get_clean();
		return $html;
	}
}
