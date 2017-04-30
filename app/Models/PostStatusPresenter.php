<?php

namespace App\Models;

class PostStatusPresenter {
	public static function present($postStatus) {
		$message = '';

		switch($postStatus) {
			case PostStatus::Pending: $message = 'Pending'; break;
			case PostStatus::Enabled: $message = 'Published'; break;
			case PostStatus::Deleted: $message = 'Deleted'; break;
			case PostStatus::ReadyForReview: $message = 'Ready For Review'; break;
			case PostStatus::RequiresRevision: $message = 'Requires Revision'; break;
		}

		return $message;
	}
}
