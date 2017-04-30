<?php

namespace App\Models;

abstract class PostStatus {
	const Pending = 0;
	const Enabled = 1;
	const Deleted = 2;
	const ReadyForReview = 3;
	const RequiresRevision = 4;
}