<?php
// 验证码类
class VCode
{
	// 随机的文本们
	private $charset;
	// 随机生成的验证码
	private $code;
	// 验证码长度
	private $codeLen = 4;
	// 图片宽度
	private $width;
	// 图片高度
	private $height;
	// 图形资源句柄
	private $img;
	// 指定的字体
	private $font;
	// 指定字体大小
	private $fontSize = 20;
	// 指定字体颜色
	private $fontColor;

	// 构造方法初始化
	public function __construct()
	{
		$this->charset = Config::get('@.VCODE_CHARSET');
		$this->font = LOCAL_STATIC_PATH . '/Fonts/' . Config::get('@.VCODE_FONT');
		$this->width = (int)Config::get('@.VCODE_WIDTH');
		$this->height = (int)Config::get('@.VCODE_HEIGHT');
	}
	// 生成随机码
	private function createCode()
	{
		$_len = strlen ( $this->charset ) - 1;
		for($i = 0; $i < $this->codeLen; $i ++)
		{
			$this->code .= $this->charset [mt_rand ( 0, $_len )];
		}
	}
	// 生成背景
	private function createBg()
	{
		$this->img = imagecreatetruecolor ( $this->width, $this->height );
		$color = imagecolorallocate ( $this->img, mt_rand ( 157, 255 ), mt_rand ( 157, 255 ), mt_rand ( 157, 255 ) );
		imagefilledrectangle ( $this->img, 0, $this->height, $this->width, 0, $color );
	}
	// 生成文字
	private function createFont()
	{
		$_x = $this->width / $this->codeLen;
		for($i = 0; $i < $this->codeLen; $i ++)
		{
			$this->fontColor = imagecolorallocate ( $this->img, mt_rand ( 0, 156 ), mt_rand ( 0, 156 ), mt_rand ( 0, 156 ) );
			imagettftext ( $this->img, $this->fontSize, mt_rand ( - 30, 30 ), $_x * $i + mt_rand ( 1, 5 ), $this->height / 1.2, $this->fontColor, $this->font, $this->code [$i] );
		}
	}
	// 生成线条、雪花
	private function createLine()
	{
		// 线条
		for($i = 0; $i < 6; $i ++)
		{
			$color = imagecolorallocate ( $this->img, mt_rand ( 0, 156 ), mt_rand ( 0, 156 ), mt_rand ( 0, 156 ) );
			imageline ( $this->img, mt_rand ( 0, $this->width ), mt_rand ( 0, $this->height ), mt_rand ( 0, $this->width ), mt_rand ( 0, $this->height ), $color );
		}
		// 雪花
		for($i = 0; $i < 100; $i ++)
		{
			$color = imagecolorallocate ( $this->img, mt_rand ( 200, 255 ), mt_rand ( 200, 255 ), mt_rand ( 200, 255 ) );
			imagestring ( $this->img, mt_rand ( 1, 5 ), mt_rand ( 0, $this->width ), mt_rand ( 0, $this->height ), '*', $color );
		}
	}
	// 输出
	private function outPut()
	{
		header ('Content-type:image/png');
		imagepng ($this->img);
		imagedestroy ($this->img);
	}
	// 对外生成
	public function doimg()
	{
		$this->createBg ();
		$this->createCode ();
		$this->createLine ();
		$this->createFont ();
		$this->outPut ();
	}
	// 获取验证码
	public function getCode()
	{
		return strtolower ($this->code);
	}
}