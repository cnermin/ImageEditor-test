<?php
require_once 'CComponent.php';
require_once '../ImageEditor/ImageEditor.php';

$images = array();
foreach (glob('images/*') as $image) {
	$size = getimagesize($image);
	$images[] = array($image, $size[0].'x'.$size[1]);
}

function get($param, $defaultValue = null) {
	return !empty($_GET[$param]) ? $_GET[$param] : $defaultValue;
}

function uniquename($ext) {
	return 'tmp/'.uniqid();
}

switch (get('act')) {
	case 'copyFrom':
		$image1 = ImageEditor::createFromFile(get('filename1'));
		$image2 = ImageEditor::createFromFile(get('filename2'));
		$image1->copyFrom($image2);
		$uniquename = uniquename('jpg');
		$image1->saveToFile($uniquename, 'jpeg');
		var_dump($_GET);
		echo "copyFrom result: <br />";
		echo $image1->width."x".$image1->height."<br />";
		echo "<hr/>";
		echo "<img src='$uniquename'/>";
		echo "<hr/>";
		break;
	case 'crop':
		$image = ImageEditor::createFromFile(get('filename'));
		$image->crop(get('x'), get('y'), get('x2', $image->getWidth()), get('y2', $image->getHeight()));
		$uniquename = uniquename('jpg');
		$image->saveToFile($uniquename, 'jpeg');
		var_dump($_GET);
		echo "crop result: <br />";
		echo $image->width."x".$image->height."<br />";
		echo "<hr/>";
		echo "<img src='$uniquename'/>";
		echo "<hr/>";
		break;
	case 'decreaseSide':
		$image = ImageEditor::createFromFile(get('filename'));
		$image->decreaseSide(get('side'), get('size', 0));
		$uniquename = uniquename('jpg');
		$image->saveToFile($uniquename, 'jpeg');
		var_dump($_GET);
		echo "decreaseSide result: <br />";
		echo $image->width."x".$image->height."<br />";
		echo "<hr/>";
		echo "<img src='$uniquename'/>";
		echo "<hr/>";
		break;
	case 'resize':
		$image = ImageEditor::createFromFile(get('filename'));
		$image->resize(get('width', $image->width), get('height', $image->height));
		$uniquename = uniquename('jpg');
		$image->saveToFile($uniquename, 'jpeg');
		var_dump($_GET);
		echo "resize result: <br />";
		echo $image->width."x".$image->height."<br />";
		echo "<hr/>";
		echo "<img src='$uniquename'/>";
		echo "<hr/>";
		break;
	case 'zoomWidthTo':
	case 'zoomHeightTo':
	case 'decreaseWidthTo':
	case 'decreaseHeightTo':
	case 'decreaseTo':
		$image = ImageEditor::createFromFile(get('filename'));
		switch (get('act')) {
			case 'zoomWidthTo':
				$image->zoomWidthTo(get('size', $image->width));
				break;
			case 'zoomHeightTo':
				$image->zoomHeightTo(get('size', $image->height));
				break;
			case 'decreaseWidthTo':
				$image->decreaseWidthTo(get('size', $image->width));
				break;
			case 'decreaseHeightTo':
				$image->decreaseHeightTo(get('size', $image->height));
				break;
			case 'decreaseTo':
				$image->decreaseTo(get('size', min($image->width, $image->height)));
				break;
		}
		$uniquename = uniquename('jpg');
		$image->saveToFile($uniquename, 'jpeg');
		var_dump($_GET);
		echo get('act')." result: <br />";
		echo $image->width."x".$image->height."<br />";
		echo "<hr/>";
		echo "<img src='$uniquename'/>";
		echo "<hr/>";
		break;
	case 'appendImageTo':
		$image1 = ImageEditor::createFromFile(get('filename1'));
		$image2 = ImageEditor::createFromFile(get('filename2'));
		$image1->appendImageTo(get('side'), $image2, (get('zoom_if_larger') ? ImageEditor::ZOOM_IF_LARGER : 0));
		$uniquename = uniquename('jpg');
		$image1->saveToFile($uniquename, 'jpeg');
		var_dump($_GET);
		echo "appendImageTo result: <br />";
		echo $image1->width."x".$image1->height."<br />";
		echo "<hr/>";
		echo "<img src='$uniquename'/>";
		echo "<hr/>";
		break;
	case 'placeImageAt':
		$image1 = ImageEditor::createFromFile(get('filename1'));
		$image2 = ImageEditor::createFromFile(get('filename2'));
		$image1->placeImageAt(get('x'), get('y'), $image2);
		$uniquename = uniquename('jpg');
		$image1->saveToFile($uniquename, 'jpeg');
		var_dump($_GET);
		echo "placeImageAt result: <br />";
		echo $image1->width."x".$image1->height."<br />";
		echo "<hr/>";
		echo "<img src='$uniquename'/>";
		echo "<hr/>";
		break;
	case 'placeImageAtCenter':
		$image1 = ImageEditor::createFromFile(get('filename1'));
		$image2 = ImageEditor::createFromFile(get('filename2'));
		$image1->placeImageAtCenter($image2);
		$uniquename = uniquename('jpg');
		$image1->saveToFile($uniquename, 'jpeg');
		var_dump($_GET);
		echo "placeImageAtCenter result: <br />";
		echo $image1->width."x".$image1->height."<br />";
		echo "<hr/>";
		echo "<img src='$uniquename'/>";
		echo "<hr/>";
		break;
	case 'rotate':
		$image = ImageEditor::createFromFile(get('filename'));
		$angle = get('angle');
		if ($angle == 'true') $angle = true;
		elseif ($angle == 'false') $angle = false;
		$image->rotate($angle);
		$uniquename = uniquename('jpg');
		$image->saveToFile($uniquename, 'jpeg');
		var_dump($_GET);
		echo "rotate result: <br />";
		echo $image->width."x".$image->height."<br />";
		echo "<hr/>";
		echo "<img src='$uniquename'/>";
		echo "<hr/>";
		break;
	case 'horizontalFlip':
	case 'verticalFlip':
		$image = ImageEditor::createFromFile(get('filename'));
		call_user_func(array($image, get('act')));
		$uniquename = uniquename('jpg');
		$image->saveToFile($uniquename, 'jpeg');
		var_dump($_GET);
		echo get('act')." result: <br />";
		echo $image->width."x".$image->height."<br />";
		echo "<hr/>";
		echo "<img src='$uniquename'/>";
		echo "<hr/>";
		break;
}

?>
<h1>copyFrom</h1>
<form>
<input type="hidden" name="act" value="copyFrom">
filename1: <select name="filename1">
	<?php foreach($images as $image) { echo "<option value='$image[0]'".(get('filename1') == $image[0] ? ' selected="selected"' : null).">$image[0] $image[1]</option>"; } ?>
</select><br />
filename2: <select name="filename2">
	<?php foreach($images as $image) { echo "<option value='$image[0]'".(get('filename2') == $image[0] ? ' selected="selected"' : null).">$image[0] $image[1]</option>"; } ?>
</select><br />
<input type="submit"/>
</form>

<h1>crop</h1>
<form>
<input type="hidden" name="act" value="crop">
filename: <select name="filename">
	<?php foreach($images as $image) { echo "<option value='$image[0]'".(get('filename') == $image[0] ? ' selected="selected"' : null).">$image[0] $image[1]</option>"; } ?>
</select><br />
x: <input name="x" value="<?= get('x', 0) ?>"/>
y: <input name="y" value="<?= get('y', 0) ?>"/>
x2: <input name="x2" value="<?= get('x2') ?>"/>
y2: <input name="y2" value="<?= get('y2') ?>"/><br />
<input type="submit"/>
</form>

<h1>decreaseSide</h1>
<form>
<input type="hidden" name="act" value="decreaseSide">
filename: <select name="filename">
	<?php foreach($images as $image) { echo "<option value='$image[0]'".(get('filename') == $image[0] ? ' selected="selected"' : null).">$image[0] $image[1]</option>"; } ?>
</select><br />
side: <select name="side">
	<?php foreach (array('top', 'right', 'bottom', 'left') as $side) { echo "<option".(get('side') == $side ? ' selected="selected"' : null).">$side</option>"; } ?>
</select>
size: <input name="size" value="<?= get('size', 0) ?>"/>
<input type="submit"/>
</form>

<h1>resize</h1>
<form>
<input type="hidden" name="act" value="resize">
filename: <select name="filename">
	<?php foreach($images as $image) { echo "<option value='$image[0]'".(get('filename') == $image[0] ? ' selected="selected"' : null).">$image[0] $image[1]</option>"; } ?>
</select><br />
width: <input name="width" value="<?= get('width') ?>"/>
height: <input name="height" value="<?= get('height') ?>"/>
<input type="submit"/>
</form>

<h1>zoomWidthTo</h1>
<form>
<input type="hidden" name="act" value="zoomWidthTo">
filename: <select name="filename">
	<?php foreach($images as $image) { echo "<option value='$image[0]'".(get('filename') == $image[0] ? ' selected="selected"' : null).">$image[0] $image[1]</option>"; } ?>
</select><br />
size: <input name="size" value="<?= get('size') ?>"/>
<input type="submit"/>
</form>

<h1>zoomHeightTo</h1>
<form>
<input type="hidden" name="act" value="zoomHeightTo">
filename: <select name="filename">
	<?php foreach($images as $image) { echo "<option value='$image[0]'".(get('filename') == $image[0] ? ' selected="selected"' : null).">$image[0] $image[1]</option>"; } ?>
</select><br />
size: <input name="size" value="<?= get('size') ?>"/>
<input type="submit"/>
</form>

<h1>decreaseWidthTo</h1>
<form>
<input type="hidden" name="act" value="decreaseWidthTo">
filename: <select name="filename">
	<?php foreach($images as $image) { echo "<option value='$image[0]'".(get('filename') == $image[0] ? ' selected="selected"' : null).">$image[0] $image[1]</option>"; } ?>
</select><br />
size: <input name="size" value="<?= get('size') ?>"/>
<input type="submit"/>
</form>

<h1>decreaseHeightTo</h1>
<form>
<input type="hidden" name="act" value="decreaseHeightTo">
filename: <select name="filename">
	<?php foreach($images as $image) { echo "<option value='$image[0]'".(get('filename') == $image[0] ? ' selected="selected"' : null).">$image[0] $image[1]</option>"; } ?>
</select><br />
size: <input name="size" value="<?= get('size') ?>"/>
<input type="submit"/>
</form>

<h1>decreaseTo</h1>
<form>
<input type="hidden" name="act" value="decreaseTo">
filename: <select name="filename">
	<?php foreach($images as $image) { echo "<option value='$image[0]'".(get('filename') == $image[0] ? ' selected="selected"' : null).">$image[0] $image[1]</option>"; } ?>
</select><br />
size: <input name="size" value="<?= get('size') ?>"/>
<input type="submit"/>
</form>

<h1>appendImageTo</h1>
<form>
<input type="hidden" name="act" value="appendImageTo">
filename1: <select name="filename1">
	<?php foreach($images as $image) { echo "<option value='$image[0]'".(get('filename1') == $image[0] ? ' selected="selected"' : null).">$image[0] $image[1]</option>"; } ?>
</select><br />
side: <select name="side">
	<?php foreach (array('top', 'right', 'bottom', 'left') as $side) { echo "<option".(get('side') == $side ? ' selected="selected"' : null).">$side</option>"; } ?>
</select>
filename2: <select name="filename2">
	<?php foreach($images as $image) { echo "<option value='$image[0]'".(get('filename2') == $image[0] ? ' selected="selected"' : null).">$image[0] $image[1]</option>"; } ?>
</select><br />
<input type="checkbox" name="zoom_if_larger" value="1"<?= (get('zoom_if_larger') ? ' checked="checked"' : null) ?>> ZOOM_IF_LARGER<br />

<input type="submit"/>
</form>

<h1>placeImageAt</h1>
<form>
<input type="hidden" name="act" value="placeImageAt">
filename1: <select name="filename1">
	<?php foreach($images as $image) { echo "<option value='$image[0]'".(get('filename1') == $image[0] ? ' selected="selected"' : null).">$image[0] $image[1]</option>"; } ?>
</select><br />
x: <input name="x" value="<?= get('x', 0) ?>"/>
y: <input name="y" value="<?= get('y', 0) ?>"/>
filename2: <select name="filename2">
	<?php foreach($images as $image) { echo "<option value='$image[0]'".(get('filename2') == $image[0] ? ' selected="selected"' : null).">$image[0] $image[1]</option>"; } ?>
</select><br />
<input type="submit"/>
</form>

<h1>placeImageAtCenter</h1>
<form>
<input type="hidden" name="act" value="placeImageAtCenter">
filename1: <select name="filename1">
	<?php foreach($images as $image) { echo "<option value='$image[0]'".(get('filename1') == $image[0] ? ' selected="selected"' : null).">$image[0] $image[1]</option>"; } ?>
</select><br />
filename2: <select name="filename2">
	<?php foreach($images as $image) { echo "<option value='$image[0]'".(get('filename2') == $image[0] ? ' selected="selected"' : null).">$image[0] $image[1]</option>"; } ?>
</select><br />
<input type="submit"/>
</form>

<h1>rotate</h1>
<form>
<input type="hidden" name="act" value="rotate">
filename: <select name="filename">
	<?php foreach($images as $image) { echo "<option value='$image[0]'".(get('filename1') == $image[0] ? ' selected="selected"' : null).">$image[0] $image[1]</option>"; } ?>
</select><br />
angle: <input name="angle" value="<?= get('angle') ?>">
<input type="submit"/>
</form>

<h1>verticalFlip</h1>
<form>
<input type="hidden" name="act" value="verticalFlip">
filename: <select name="filename">
	<?php foreach($images as $image) { echo "<option value='$image[0]'".(get('filename1') == $image[0] ? ' selected="selected"' : null).">$image[0] $image[1]</option>"; } ?>
</select><br />
<input type="submit"/>
</form>

<h1>horizontalFlip</h1>
<form>
<input type="hidden" name="act" value="horizontalFlip">
filename: <select name="filename">
	<?php foreach($images as $image) { echo "<option value='$image[0]'".(get('filename1') == $image[0] ? ' selected="selected"' : null).">$image[0] $image[1]</option>"; } ?>
</select><br />
<input type="submit"/>
</form>
