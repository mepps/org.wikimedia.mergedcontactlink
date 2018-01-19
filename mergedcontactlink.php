<?php

require_once 'mergedcontactlink.civix.php';
use CRM_Mergedcontactlink_ExtensionUtil as E;

/**
 * Implements hook_civicrm_config().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_config
 */
function mergedcontactlink_civicrm_config(&$config) {
  _mergedcontactlink_civix_civicrm_config($config);
}

/**
 * Implements hook_civicrm_xmlMenu().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_xmlMenu
 */
function mergedcontactlink_civicrm_xmlMenu(&$files) {
  _mergedcontactlink_civix_civicrm_xmlMenu($files);
}

/**
 * Implements hook_civicrm_install().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_install
 */
function mergedcontactlink_civicrm_install() {
  CRM_Core_DAO::executeQuery( "ALTER TABLE civicrm_contact ADD COLUMN merged_contact_id integer" ); 
}

/**
 * Implements hook_civicrm_postInstall().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_postInstall
 */
function mergedcontactlink_civicrm_postInstall() {
  $batch = 100;
  $maxId = CRM_Core_DAO::singleValueQuery("SELECT max(id) FROM civicrm_activity");
  $activity_type_id = civicrm_api3('OptionValue', 'getSingle', array(
    'label' => 'Contact Deleted by Merge',
    'return' => 'value',
  ));
  $query = "SELECT ca.target_id as deleted_contact_id, cap.contact_id as new_contact_id
             FROM civicrm_activity ca
             JOIN civicrm_activity_contact cap ON ca.parent_id=cap.activity_id AND cap.record_type_id=3
             WHERE (SELECT count(*) FROM civicrm_activity_contact WHERE record_type_id=1 AND activity_id=ca.id)=0
             AND ca.activity_type_id=%1 AND ca.id BETWEEN %2 AND %3";
  $params = array(1 => array($activity_type_id['value'], 'Integer'));
  for ($startId = 0; $startId < $maxId; $startId += $batch) {
    $endId = $startId + $batch;
    $params[2] = array($startId, 'Integer');
    $params[3] = array($endId, 'Integer');
    $activities = CRM_Core_DAO::executeQuery($query, $params);
    while ($activities->fetch()) {
      $params = array( 1 => array( $activities->new_contact_id, 'Integer' ), 2 => array( $activities->deleted_contact_id, 'Integer' ) );
      CRM_Core_DAO::executeQuery( "UPDATE civicrm_contact SET merged_contact_id=%1 WHERE id=%2" );
    }
  }

}

/**
 * Implements hook_civicrm_uninstall().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_uninstall
 */
function mergedcontactlink_civicrm_uninstall() {
  CRM_Core_DAO::executeQuery( "ALTER TABLE civicrm_contact DROP COLUMN merged_contact_id" );
}

/**
 * Implements hook_civicrm_enable().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_enable
 */
function mergedcontactlink_civicrm_enable() {
  _mergedcontactlink_civix_civicrm_enable();
}

/**
 * Implements hook_civicrm_disable().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_disable
 */
function mergedcontactlink_civicrm_disable() {
  _mergedcontactlink_civix_civicrm_disable();
}

/**
 * Implements hook_civicrm_upgrade().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_upgrade
 */
function mergedcontactlink_civicrm_upgrade($op, CRM_Queue_Queue $queue = NULL) {
  return _mergedcontactlink_civix_civicrm_upgrade($op, $queue);
}

/**
 * Implements hook_civicrm_managed().
 *
 * Generate a list of entities to create/deactivate/delete when this module
 * is installed, disabled, uninstalled.
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_managed
 */
function mergedcontactlink_civicrm_managed(&$entities) {
  _mergedcontactlink_civix_civicrm_managed($entities);
}

/**
 * Implements hook_civicrm_caseTypes().
 *
 * Generate a list of case-types.
 *
 * Note: This hook only runs in CiviCRM 4.4+.
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_caseTypes
 */
function mergedcontactlink_civicrm_caseTypes(&$caseTypes) {
  _mergedcontactlink_civix_civicrm_caseTypes($caseTypes);
}

/**
 * Implements hook_civicrm_angularModules().
 *
 * Generate a list of Angular modules.
 *
 * Note: This hook only runs in CiviCRM 4.5+. It may
 * use features only available in v4.6+.
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_angularModules
 */
function mergedcontactlink_civicrm_angularModules(&$angularModules) {
  _mergedcontactlink_civix_civicrm_angularModules($angularModules);
}

/**
 * Implements hook_civicrm_alterSettingsFolders().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_alterSettingsFolders
 */
function mergedcontactlink_civicrm_alterSettingsFolders(&$metaDataFolders = NULL) {
  _mergedcontactlink_civix_civicrm_alterSettingsFolders($metaDataFolders);
}

// --- Functions below this ship commented out. Uncomment as required. ---

/**
 * Implements hook_civicrm_preProcess().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_preProcess
 *
function mergedcontactlink_civicrm_preProcess($formName, &$form) {

} // */

/**
 * Implements hook_civicrm_navigationMenu().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_navigationMenu
 *
function mergedcontactlink_civicrm_navigationMenu(&$menu) {
  _mergedcontactlink_civix_insert_navigation_menu($menu, NULL, array(
    'label' => E::ts('The Page'),
    'name' => 'the_page',
    'url' => 'civicrm/the-page',
    'permission' => 'access CiviReport,access CiviContribute',
    'operator' => 'OR',
    'separator' => 0,
  ));
  _mergedcontactlink_civix_navigationMenu($menu);
} // */
