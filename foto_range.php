<?header('Content-Type: text/html; charset=utf-8'); 
//finds next photographer in range

//underscore stuff is legacy, camel-cased is php 5

include("shared_inc/language.inc.php");
include("shared_inc/wiki_functions.inc.php");
include('next_inc/OfferingUser.php');	
include('next_inc/OfferPage.php');	
include('next_inc/GeoLocation.php');	


$inc_dir = "next_inc/lang";
//get the language file 
$user_lang = read_language();
get_language('en', $inc_dir); //not translated messages will be printed in English
get_language($user_lang, $inc_dir);

$is_debug = ($_REQUEST['debug']=="on" || $_REQUEST['debug']=="true" );

$server = "$lang.$project.org";

$article_to = $_REQUEST['article_to'];
if($article_to == "")
{
	echo '<form>';
	echo $messages['lang'] . ': <input name="lang" value="' . $lang .'"/> ' . $messages['lang_example'] .'<br>';
	echo $messages['project'] . ': <input name="project" value="' . $project .'"/>' . $messages['project_example'] .'<br>';
	echo $messages['article_to'] . ': <input name="article_to" value="' . $article_to .'"/>' . $messages['article_to_descr'] .'<br>';
	echo '<input type="submit" value="'. $messages['find_next'] .'"/>';
	echo '</form>';

}
else
{
	
	$linkToArticleTo = "<a href=\"https://$server/wiki/".name_in_url($article_to)."\">$article_to</a>";
	echo '<h1>' . str_replace('_ARTICLE_TO_', $linkToArticleTo, $messages['distance_to']) .'</h1>';
	$locTo = new GeoLocation($article_to, $server);
	if($locTo->IsValid())
	{
		
		foreach(OfferPage::GetAvailableServers() as $oneServer)
		{
			$offerpage = new OfferPage($oneServer);
			echo "<h2><a href=\"https://$oneServer/wiki/$offerpage->pageEncoded\">$oneServer</a></h2>";
			$offerpage->ListUsersToRequest($locTo);
		}
	}
	else
	{
		echo str_replace('_LOCATION_', $requested, $messages['no_coordinates']);
	}
	
	echo "<br><br><a href=\"?lang=$lang&project=$project\">".$messages['new_request']."</a>";
}

function print_debug($str)
{
	global $is_debug;
	if($is_debug)
	{
		echo $str;
	}
}

?>