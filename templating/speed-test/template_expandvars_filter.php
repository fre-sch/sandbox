<?php
class template_expandvars_filter extends php_user_filter {
	private $names = array();
	/**
	 * @see php_user_filter::filter()
	 *
	 * @param resource $in
	 * @param resource $out
	 * @param int $consumed
	 * @param bool $closing
	 * @return int
	 */
	public function filter( $in, $out, &$consumed, $closing ) {
		while ( $bucket = stream_bucket_make_writeable( $in ) ) {
			$bucket->data = str_replace(
				$this->names,
				$this->params['vars'],
				$bucket->data );
			$consumed += $bucket->datalen;
			stream_bucket_append( $out, $bucket );
		}
		return PSFS_PASS_ON;
	}
	/**
	 * @see php_user_filter::onClose()
	 */
	public function onClose() {
	}
	/**
	 * @see php_user_filter::onCreate()
	 * @return bool
	 */
	public function onCreate() {
		$this->names = array_keys( $this->params['vars'] );
	}
}
stream_filter_register( 'expandvars', 'template_expandvars_filter' );
