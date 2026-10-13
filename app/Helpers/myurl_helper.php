<?php 

if(!function_exists('rewrite_url')){
	function rewrite_url($param = '', $style = '', $module = '', $prefix = '' , $suffix = ''){
		$data = [];
		switch ($style) {
			case 'normal':
				foreach ($param as $key => $value) {
					$checkLink = explode('.', $value['canonical']);
					$checkid = explode('-data-id=' , $checkLink[0]);
					$link = removeutf8($value['canonical']);
					if(isset($checkid)){
						$link = slug($link);
						$link = $checkid[0].'-data-id='.$value['id'].''.$suffix;
					}else{
						if(!isset($checkLink[1])){
							$link = slug($link);
							$link = $link.'-'.$prefix.'-data-id='.$value['id'].''.$suffix;
						}
					}
					$data[] = $link;
				}
				break;
		}

		return $data;
	}
}

if(!function_exists('write_url')){
	function write_url($canonical = '', $suffix = TRUE, $fulllink = FALSE){
		if (empty($canonical)) {
			return;	// giu nguyen hanh vi cu: canonical rong thi khong tra ve gi
		}

		$locale = request()->getLocale();
		$domain = ($fulllink == TRUE) ? BASE_URL : '';

		/* Ngon ngu mac dinh khong co tien to trong URL:
		 *     vi => /lien-he.html
		 *     kr => /kr/lien-he.html
		 *
		 * Truoc day so sanh cung chuoi 'vi'. Nay lay tu App::$defaultLocale,
		 * nen doi ngon ngu mac dinh khong phai sua lai ham nay - va khong con
		 * mot ma ngon ngu nao viet cung trong helper.
		 */
		$prefix = ($locale === config('App')->defaultLocale) ? '' : $locale . '/';

		return $domain . $prefix . $canonical . (($suffix == TRUE) ? HTSUFFIX : '');
	}
}

?>

