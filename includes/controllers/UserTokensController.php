<?php
/**
 * The User Tokens Controller Class.
 *
 * @package  WP_All_Forms_API
 * @since 1.0.0
 */

namespace Includes\Controllers;

use Includes\Models\UserTokensModel;
use Includes\Plugins\Helpers\Pagination;

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

/**
 * Class UserController
 *
 * Manipulate User info
 *
 * @since 1.0.0
 */
class UserTokensController {
	/**
	 * User Tokens Model
	 *
	 * @var UserTokensModel
	 */
	private $user_tokens_model;

	/**
	 * Pagination Helper
	 *
	 * @var Pagination
	 */
	private $pagination_helper;

	/**
	 * UserTokensController constructor.
	 */
	public function __construct() {
		$this->user_tokens_model = new UserTokensModel();
		$this->pagination_helper = new Pagination();
	}

	/**
	 * Get UserTokensController items by page.
	 *
	 * @param int $page The page.
	 *
	 * @return array $UserTokensModel the items.
	 */
	public function paginate( $page = 0 ) {

		$count                      = $this->user_tokens_model->mumber_items();
		$number_of_records_per_page = $this->pagination_helper->getNumberofRecordsPerPage();
		$items                      = $this->user_tokens_model->users_tokens( $page, $number_of_records_per_page );

		$results = $this->pagination_helper->prepareDataForRestWithPagination( $count, $items );

		return $results;
	}


}
