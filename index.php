<!DOCTYPE html>
<html lang="en">
<head>
	<meta http-equiv="Content-type" content="text/html; charset=utf-8">
	<title>PHP PubMed API Wrapper by Asif Rahman</title>
	<style type="text/css" media="screen">
		body{font-family:"Helvetica Neue",Helvetica,sans-serif;font-size:14px;line-height:19px;}
		h1 a{color:#A00;}h1 a:hover{text-decoration:none;}
		pre,code{font-family:Monaco,monospace;font-size:12px;}pre{padding:20px;background:#EEE;}
		a{color:#00a;text-decoration:none;}a:hover{color:#000;text-decoration:underline;}
		table{width:100%;}
		th{text-align:left;border-bottom:1px solid #CCC;padding:5px;}
		td{vertical-align:top;padding:5px;}
		#wrap{width:960px;margin:0 auto;}
		input[type=text]{width:300px;font-size:inherit;font-family:inherit;border:1px solid #BBB;padding:5px;}
	</style>
</head>
<body>

<div id="wrap">
<h1><a href="https://github.com/asifr/PHP-PubMed-API-Wrapper">PHP PubMed API Wrapper</a></h1>
<p><strong>Author</strong>: <a href="https://github.com/asifr">Asif Rahman</a></p>

<p>This demo is minimal in scope. It uses the PubMedAPI class to query PubMed and return the results in a table.</p>

<form action="" method="get" accept-charset="utf-8">
	<p><label for="search_term">Search term: </label> <input type="text" name="term" value="<?php echo isset($_GET['term'])?stripslashes($_GET['term']):'electrical stimulation network dynamics reato 2010'; ?>" id="search_term"> <input type="submit" value="Search PubMed"></p>
</form>

<?php
if (isset($_GET['term']) && $_GET['term'] != '') {
	$term = stripslashes(urldecode($_GET['term']));

	/**
	 * Removes any "bad" characters (characters which mess with the display of a page, are invisible, etc) from user input
	 */
	function remove_bad_characters()
	{
		global $bad_utf8_chars;

		$bad_utf8_chars = array("\0", "\xc2\xad", "\xcc\xb7", "\xcc\xb8", "\xe1\x85\x9F", "\xe1\x85\xA0", "\xe2\x80\x80", "\xe2\x80\x81", "\xe2\x80\x82", "\xe2\x80\x83", "\xe2\x80\x84", "\xe2\x80\x85", "\xe2\x80\x86", "\xe2\x80\x87", "\xe2\x80\x88", "\xe2\x80\x89", "\xe2\x80\x8a", "\xe2\x80\x8b", "\xe2\x80\x8e", "\xe2\x80\x8f", "\xe2\x80\xaa", "\xe2\x80\xab", "\xe2\x80\xac", "\xe2\x80\xad", "\xe2\x80\xae", "\xe2\x80\xaf", "\xe2\x81\x9f", "\xe3\x80\x80", "\xe3\x85\xa4", "\xef\xbb\xbf", "\xef\xbe\xa0", "\xef\xbf\xb9", "\xef\xbf\xba", "\xef\xbf\xbb", "\xE2\x80\x8D");

		function _remove_bad_characters($array) {
			global $bad_utf8_chars;
			return is_array($array) ? array_map('_remove_bad_characters', $array) : str_replace($bad_utf8_chars, '', $array);
		}

		$_GET = _remove_bad_characters($_GET);
		$_POST = _remove_bad_characters($_POST);
		$_COOKIE = _remove_bad_characters($_COOKIE);
		$_REQUEST = _remove_bad_characters($_REQUEST);
	}

	remove_bad_characters();

	include('PubMedAPI.php');
	$PubMedAPI = new AR_PubMedAPI();
	if (isset($_GET['page'])) {
		$PubMedAPI->retstart = $PubMedAPI->retmax*((int)$_GET['page'] - 1)+1;
	}
	$results = $PubMedAPI->query($term, false);
}
?>

<?php if (!empty($results)): ?>
	<p>Search results for <strong><?php echo urldecode($PubMedAPI->term); ?></strong> (<?php echo $PubMedAPI->count; ?> results, showing max 5)</p>
	<table border="0" cellspacing="0" cellpadding="0">
		<tr>
			<th>PMID</th>
			<th>Title</th>
			<th>Authors</th>
			<th>Journal</th>
			<th>Year</th>
		</tr>
		<?php foreach ($results as $result): ?>
		<tr>
			<td><?php echo $result['pmid']; ?></td>
			<td><a href="http://www.ncbi.nlm.nih.gov/pubmed/<?php echo $result['pmid']; ?>" target="_blank"><?php echo $result['title']; ?></a></td>
			<td><?php echo implode(", ",$result['authors']); ?></td>
			<td><?php echo $result['journalabbrev']; ?></td>
			<td><?php echo $result['year']; ?></td>
		</tr>
		<?php endforeach; ?>
	</table>
<?php endif; ?>

<h2><strong>Example implementation:</strong></h2>
<pre><code>include('PubMedAPI.php');
$search_term = 'electrical stimulation network dynamics reato 2010';
$PubMedAPI = new PubMedAPI();
$results = $PubMedAPI->query($search_term);
</code></pre>

</div>

</body>
</html>