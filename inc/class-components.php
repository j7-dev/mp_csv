<?php

declare(strict_types=1);

namespace MorePower\CSV;



class Components
{
	static public function renderAvatar(array $props): string
	{

		extract(shortcode_atts(array(
			'src' => 'data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0iVVRGLTgiPz4KPHN2ZyB2aWV3Qm94PSIwIDAgMTI4IDEyOCIgdmVyc2lvbj0iMS4xIiB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHJvbGU9ImltZyIgYXJpYS1sYWJlbD0ieHhsYXJnZSI+CiAgICA8Zz4KICAgICAgICA8Y2lyY2xlIGN4PSI2NCIgY3k9IjY0IiByPSI2NCIgZmlsbD0iIzg5OTNhNCIgLz4KICAgICAgICA8Zz4KICAgICAgICAgICAgPHBhdGggZmlsbD0iI2ZmZiIKICAgICAgICAgICAgICAgIGQ9Ik0xMDMsMTAyLjEzODggQzkzLjA5NCwxMTEuOTIgNzkuMzUwNCwxMTggNjQuMTYzOCwxMTggQzQ4LjgwNTYsMTE4IDM0LjkyOTQsMTExLjc2OCAyNSwxMDEuNzg5MiBMMjUsOTUuMiBDMjUsODYuODA5NiAzMS45ODEsODAgNDAuNiw4MCBMODcuNCw4MCBDOTYuMDE5LDgwIDEwMyw4Ni44MDk2IDEwMyw5NS4yIEwxMDMsMTAyLjEzODggWiIgLz4KICAgICAgICAgICAgPHBhdGggZmlsbD0iI2ZmZiIKICAgICAgICAgICAgICAgIGQ9Ik02My45OTYxNjQ3LDI0IEM1MS4yOTM4MTM2LDI0IDQxLDM0LjI5MzgxMzYgNDEsNDYuOTk2MTY0NyBDNDEsNTkuNzA2MTg2NCA1MS4yOTM4MTM2LDcwIDYzLjk5NjE2NDcsNzAgQzc2LjY5ODUxNTksNzAgODcsNTkuNzA2MTg2NCA4Nyw0Ni45OTYxNjQ3IEM4NywzNC4yOTM4MTM2IDc2LjY5ODUxNTksMjQgNjMuOTk2MTY0NywyNCIgLz4KICAgICAgICA8L2c+CiAgICA8L2c+Cjwvc3ZnPgo=',
			'title' => '',
			'size' => '6rem',
			'class' => 'text-center'
		), $props));

		$default_src = 		ob_start();
?>
		<div class="avatar <?= $class ?>">
			<div class="group inline-block rounded-full ring ring-primary ring-offset-base-100 ring-offset-2 overflow-hidden" style="width: <?= $size; ?>; height: <?= $size; ?>">
				<img class="group-hover:scale-[120%] duration-300" src="<?= $src ? $src : $default_src ?>" />
			</div>
			<?php if ($title) : ?>
				<h3 class="mt-4 font-semibold text-lg"><?= $title ?></h3>
			<?php endif; ?>
		</div>

<?php
		$html = ob_get_clean();
		return $html;
	}
}
