<!DOCTYPE html>
<html lang="en">
<head>
	<meta http-equiv="Content-type" content="text/html; charset=utf-8">
	<title>PHP PubMed API Wrapper by Asif Rahman</title>
	<style type="text/css" media="screen">
		body{font-family:"Helvetica Neue",Helvetica,sans-serif;font-size:14px;}
		pre,code{font-family:Monaco,monospace;font-size:12px;}pre{padding:20px;background:#EEE;}
		a{color:#0080FF;text-decoration:none;}a:hover{color:#FF0080;}
		table{width:800px;}
		th{text-align:left;}
		td{vertical-align:top;}
		#wrap{width:960px;margin:0 auto;}
	</style>
</head>
<body>

<a href="https://github.com/you"><img style="position: absolute; top: 0; right: 0; border: 0;" src="https://s3.amazonaws.com/github/ribbons/forkme_right_orange_ff7600.png" alt="Fork me on GitHub"></a>

<div id="wrap">
<h1>PHP PubMed API Wrapper</h1>
<p><strong>Author</strong>: Asif Rahman</p>

<p><a href="https://github.com/asifr/PHP-PubMed-API-Wrapper">Download</a></p>

<h2>Description</h2>

<p>The PubMed API Wrapper provides a convenient method to query the PubMed database.</p>
<ul>
	<li>Use a proxy server while querying</li>
	<li>Limit total number of search results</li>
	<li>Pagination splits search results among multiple pages</li>
	<li>Optional exact search term matching</li>
	<li>Cache search results as JSON formatted text files</li>
	<li>Load search results from cache</li>
	<li>Toggle lifetime of cache results</li>
</ul>
<p>See the script in action at <a href="http://soterixmedical.com/learn/publications.php">Soterix Medical</a>.</p>

<h2>Demo</h2>

<form action="" method="get" accept-charset="utf-8">
	<p><label for="search_term">Search term: </label> <input type="text" name="term" value="" id="search_term"> <input type="submit" value="Search PubMed"></p>
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

	include('AR_PubMedAPI.php');
	$AR_PubMedAPI = new AR_PubMedAPI();
	if (isset($_GET['page'])) {
		$AR_PubMedAPI->retstart = $AR_PubMedAPI->retmax*((int)$_GET['page'] - 1)+1;
	}
	$results = $AR_PubMedAPI->query($term, false);
}
?>

<?php if (!empty($results)): ?>
	<p>Search results for <strong><?php echo urldecode($AR_PubMedAPI->term); ?></strong> (<?php echo $AR_PubMedAPI->count; ?> results, showing max 5)</p>
	<table border="0" cellspacing="5" cellpadding="5">
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

<h2>Documentation</h2>

<p><strong>Example implementation:</strong></p>
<pre><code>include('AR_PubMedAPI.php');
$search_term = 'electrical stimulation network dynamics reato 2010';
$AR_PubMedAPI = new AR_PubMedAPI();
$results = $AR_PubMedAPI->query($search_term);
</code></pre>

<h2>Copyright and License</h2>

<p>The PHP script is free software, available under the terms of the BSD-style open source license reproduced below, or, at your option, under the <a href="http://www.gnu.org/licenses/gpl-2.0.txt">GNU General Public License version 2</a> or a later version.</p>

<p>PHP PubMed API Wrapper<br />
Copyright &copy; 2012 Asif Rahman<br />
All rights reserved.</p>

<p>Redistribution and use in source and binary forms, with or without modification, are permitted provided that the following conditions are met:</p>
<p>Redistributions of source code must retain the above copyright notice, this list of conditions and the following disclaimer.</p>
<p>Redistributions in binary form must reproduce the above copyright notice, this list of conditions and the following disclaimer in the documentation and/or other materials provided with the distribution.</p>
<p>Neither the name "PHP PubMedAPI Wrapper" nor the names of its contributors may be used to endorse or promote products derived from this software without specific prior written permission.</p>
<p>This software is provided by the copyright holders and contributors "as is" and any express or implied warranties, including, but not limited to, the implied warranties of merchantability and fitness for a particular purpose are disclaimed. In no event shall the copyright owner or contributors be liable for any direct, indirect, incidental, special, exemplary, or consequential damages (including, but not limited to, procurement of substitute goods or services; loss of use, data, or profits; or business interruption) however caused and on any theory of liability, whether in contract, strict liability, or tort (including negligence or otherwise) arising in any way out of the use of this software, even if advised of the possibility of such damage.</p>
</div>

</body>
</html>