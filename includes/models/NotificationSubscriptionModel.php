<?php
/**
 * The Notification Subscription Model Class.
 *
 * @package  WP_All_Forms_API
 * @since 1.0.0
 */

namespace Includes\Models;

use Includes\Plugins\Constant;
use Includes\Models\UserDevicesModel;
use Includes\Models\NotificationTypeModel;

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

/**
 * Class NotificationSubscriptionModel
 *
 * Hendler with notification_subscription table
 *
 * @since 1.0.0
 */
class NotificationSubscriptionModel {

	/**
	 * Table name
	 *
	 * @var string
	 */
	private $table_name;

	/**
	 * NotificationTypeModel constructor.
	 */
	public function __construct() {
		global $wpdb;
		$this->table_name = $wpdb->prefix . Constant::TABLE_NOTIFICATION_SUBSCRIPTION;
	}

	/**
	 * Subscribe user to all notification
	 *
	 * @param string $expo_token The user token for push notification.
	 *
	 * @return boolean
	 */
	public function subscribe_user_all_notificions_by_expo_token( $expo_token ) {

		global $wpdb;

		$device = ( new UserDevicesModel() )->get_register_by_user_expo_token( $expo_token );

		if ( empty( $device ) ) {
			return false;
		}

		$notifications_types = ( new NotificationTypeModel() )->get_all_notification_type();

		$controll = true;

		foreach ( $notifications_types as $key => $value ) {

			$item                         = array();
			$item['user_devices_id']      = $device->id;
			$item['notification_type_id'] = $value->id;
			$item['enabled']              = true;

			// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching
			$reusult = $wpdb->insert(
				$this->table_name,
				$item
			);

			if ( ! $reusult ) {
				$controll = false;
			}
		}

		return $controll;

	}

	/**
	 * Get user notification subscription
	 *
	 * @param string $expo_token The user token for push notification.
	 *
	 * @return array
	 */
	public function fetch_subscriptions_by_expo_token( $expo_token ) {

		global $wpdb;

		$device = ( new UserDevicesModel() )->get_register_by_user_expo_token( $expo_token );

		if ( empty( $device ) ) {
			return array();
		}

		$id = $device->id;

		$table_notification_type = $wpdb->prefix . Constant::TABLE_NOTIFICATION_TYPE;
		$sql                     = "SELECT afa_tns.id, afa_tns.user_devices_id, afa_tns.notification_type_id, afa_tns.enabled, afa_tnt.type, afa_tnt.title FROM {$this->table_name} afa_tns INNER JOIN {$table_notification_type} afa_tnt ON afa_tns.notification_type_id = afa_tnt.id WHERE afa_tns.user_devices_id=%d ";

		$sql = $wpdb->prepare( $sql, array( $id ) );// phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared

		// phpcs:ignore
		$results = $wpdb->get_results( $sql, OBJECT );

		return $results;

	}

}
