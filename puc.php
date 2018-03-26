<?php
/**
 * Plugin Name: Plugin (and theme) update checker wrapper for SRD
 * Description: Uses PUC to check for plugin and theme releases from GitHub
 * Author: charliecek
 * Author URI: http://charliecek.eu/
 * Version: 1.1.0
 */

require __DIR__.'/plugin-update-checker-4.4/plugin-update-checker.php';

$aPluginSlugs = array(
  'all-in-one-event-calendar-fixes' => '%%slug%%',
  'flashmob-organizer-profile' => '%%slug%%',
  'flashmob-stats-parser' => '%%slug%%',
  'polylang_language_fallback' => '%%slug%%',
  'srd-wp-mail-smtp-fixes' => '%%slug%%',
  'puc' => '%%slug%%',
);

$aDefaultProperties = array(
  'wp-content-path'   => '/plugins/%%slug%%/%%slug%%.php',
  'github-repo-name'  => '%%slug%%',
  'github-branch'     => 'master',
);
$updateCheckers = array();
foreach ($aPluginSlugs as $strPluginSlug => $mixProperties) {
  if (is_array($mixProperties)) {
    $aProperties = $mixProperties;
    // Check format of set properties //
    foreach ($aProperties as $strKey => $strVal) {
      if (!is_string($strVal)) {
        continue 2;
      }
    }
    // Add missing properties //
    foreach ($aDefaultProperties as $strKey => $strVal) {
      if (!isset($aProperties[$strKey])) {
        $aProperties[$strKey] = $strVal;
      }
    }
  } elseif (is_string($mixProperties)) {
    $aProperties = $aDefaultProperties;
    $aProperties['github-repo-name'] = $mixProperties;
  } else {
    continue;
  }
  
  // Replace placeholders //
  foreach ($aProperties as $strKey => $strVal) {
    $aProperties[$strKey] = str_replace( '%%slug%%', $strPluginSlug, $strVal );
    if (strpos($aProperties[$strKey], '%%') !== false) {
      continue 2;
    }
  }
  
  $strPluginPath = dirname(__DIR__).$aProperties['wp-content-path'];
  // die("<pre>".var_export(array($strPluginPath, realpath($strPluginPath)), true)."</pre>");
  $updateCheckers[$strPluginSlug] = Puc_v4_Factory::buildUpdateChecker(
    'https://github.com/charliecek/'.$aProperties['github-repo-name'].'/',
    $strPluginPath,
    $strPluginSlug
  );
  $updateCheckers[$strPluginSlug]->setBranch($aProperties['github-branch']);
}

//Optional: If you're using a private repository, specify the access token like this:
// $myUpdateChecker->setAuthentication('your-token-here');

//Optional: Set the branch that contains the stable release.
// $myUpdateChecker->setBranch('stable-branch-name');