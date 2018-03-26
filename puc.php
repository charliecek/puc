<?php
/**
 * Plugin Name: Plugin (and theme) update checker wrapper for SRD
 * Description: Uses PUC to check for plugin and theme releases from GitHub
 * Author: charliecek
 * Author URI: http://charliecek.eu/
 * Version: 1.0.0
 */

require __DIR__.'/plugin-update-checker-4.4/plugin-update-checker.php';

$aPluginSlugs = array(
  'all-in-one-event-calendar-fixes' => 'all-in-one-event-calendar-fixes',
  'flashmob-organizer-profile' => 'flashmob-organizer-profile',
);

$updateCheckers = array();
foreach ($aPluginSlugs as $strPluginSlug => $strGitHubRepoName) {
  
  $strPluginPath = dirname(__DIR__)."/plugins/".$strPluginSlug."/".$strPluginSlug.".php";
  // die("<pre>".var_export(array($strPluginPath, realpath($strPluginPath)), true)."</pre>");
  $updateCheckers[$strPluginSlug] = Puc_v4_Factory::buildUpdateChecker(
    'https://github.com/charliecek/'.$strGitHubRepoName.'/',
    $strPluginPath,
    $strPluginSlug
  );
  $updateCheckers[$strPluginSlug]->setBranch('master');
}

//Optional: If you're using a private repository, specify the access token like this:
// $myUpdateChecker->setAuthentication('your-token-here');

//Optional: Set the branch that contains the stable release.
// $myUpdateChecker->setBranch('stable-branch-name');