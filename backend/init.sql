
   INFO  Running migrations.

  0001_01_01_000000_create_users_table ...........................................................................
  ⇂ create table `users` (`id` bigint unsigned not null auto_increment primary key, `name` varchar(255) not null, `email` varchar(255) not null, `password` varchar(255) not null, `balance` decimal(8, 2) not null default '0', `phone` varchar(255) null, `remember_token` varchar(100) null, `created_at` timestamp null, `updated_at` timestamp null) default character set utf8mb4 collate 'utf8mb4_unicode_ci'
  ⇂ alter table `users` add unique `users_email_unique`(`email`)
  ⇂ create table `password_reset_tokens` (`email` varchar(255) not null, `token` varchar(255) not null, `created_at` timestamp null, primary key (`email`)) default character set utf8mb4 collate 'utf8mb4_unicode_ci'
  ⇂ create table `sessions` (`id` varchar(255) not null, `user_id` bigint unsigned null, `ip_address` varchar(45) null, `user_agent` text null, `payload` longtext not null, `last_activity` int not null, primary key (`id`)) default character set utf8mb4 collate 'utf8mb4_unicode_ci'
  ⇂ alter table `sessions` add index `sessions_user_id_index`(`user_id`)
  ⇂ alter table `sessions` add index `sessions_last_activity_index`(`last_activity`)
  0001_01_01_000001_create_cache_table ...........................................................................
  ⇂ create table `cache` (`key` varchar(255) not null, `value` mediumtext not null, `expiration` int not null, primary key (`key`)) default character set utf8mb4 collate 'utf8mb4_unicode_ci'
  ⇂ create table `cache_locks` (`key` varchar(255) not null, `owner` varchar(255) not null, `expiration` int not null, primary key (`key`)) default character set utf8mb4 collate 'utf8mb4_unicode_ci'
  0001_01_01_000002_create_jobs_table ............................................................................
  ⇂ create table `jobs` (`id` bigint unsigned not null auto_increment primary key, `queue` varchar(255) not null, `payload` longtext not null, `attempts` tinyint unsigned not null, `reserved_at` int unsigned null, `available_at` int unsigned not null, `created_at` int unsigned not null) default character set utf8mb4 collate 'utf8mb4_unicode_ci'
  ⇂ alter table `jobs` add index `jobs_queue_index`(`queue`)
  ⇂ create table `job_batches` (`id` varchar(255) not null, `name` varchar(255) not null, `total_jobs` int not null, `pending_jobs` int not null, `failed_jobs` int not null, `failed_job_ids` longtext not null, `options` mediumtext null, `cancelled_at` int null, `created_at` int not null, `finished_at` int null, primary key (`id`)) default character set utf8mb4 collate 'utf8mb4_unicode_ci'
  ⇂ create table `failed_jobs` (`id` bigint unsigned not null auto_increment primary key, `uuid` varchar(255) not null, `connection` text not null, `queue` text not null, `payload` longtext not null, `exception` longtext not null, `failed_at` timestamp not null default CURRENT_TIMESTAMP) default character set utf8mb4 collate 'utf8mb4_unicode_ci'
  ⇂ alter table `failed_jobs` add unique `failed_jobs_uuid_unique`(`uuid`)
  2025_03_10_102158_create_categories_table ......................................................................
  ⇂ create table `categories` (`id` bigint unsigned not null auto_increment primary key, `user_id` bigint unsigned not null, `name` varchar(255) not null, `type` enum('income', 'expense') not null, `created_at` timestamp null, `updated_at` timestamp null) default character set utf8mb4 collate 'utf8mb4_unicode_ci'
  ⇂ alter table `categories` add constraint `categories_user_id_foreign` foreign key (`user_id`) references `users` (`id`) on delete cascade
  2025_03_10_102229_create_transactions_table ....................................................................
  ⇂ create table `transactions` (`id` bigint unsigned not null auto_increment primary key, `user_id` bigint unsigned not null, `category_id` bigint unsigned null, `amount` decimal(8, 2) not null, `type` enum('income', 'expense') not null, `description` text null, `transaction_date` date not null, `is_recurring` tinyint(1) not null default '0', `created_at` timestamp null, `updated_at` timestamp null) default character set utf8mb4 collate 'utf8mb4_unicode_ci'
  ⇂ alter table `transactions` add constraint `transactions_user_id_foreign` foreign key (`user_id`) references `users` (`id`) on delete cascade
  ⇂ alter table `transactions` add constraint `transactions_category_id_foreign` foreign key (`category_id`) references `categories` (`id`) on delete set null
  2025_03_10_102239_create_budgets_table .........................................................................
  ⇂ create table `budgets` (`id` bigint unsigned not null auto_increment primary key, `user_id` bigint unsigned not null, `category_id` bigint unsigned null, `budget_limit` decimal(8, 2) not null, `start_date` date not null, `end_date` date not null, `created_at` timestamp null, `updated_at` timestamp null) default character set utf8mb4 collate 'utf8mb4_unicode_ci'
  ⇂ alter table `budgets` add constraint `budgets_user_id_foreign` foreign key (`user_id`) references `users` (`id`) on delete cascade
  ⇂ alter table `budgets` add constraint `budgets_category_id_foreign` foreign key (`category_id`) references `categories` (`id`) on delete set null
  2025_03_10_102249_create_targets_table .........................................................................
  ⇂ create table `targets` (`id` bigint unsigned not null auto_increment primary key, `user_id` bigint unsigned not null, `name` varchar(255) not null, `target_amount` decimal(8, 2) not null, `saved_amount` decimal(8, 2) not null, `deadline` date not null, `created_at` timestamp null, `updated_at` timestamp null) default character set utf8mb4 collate 'utf8mb4_unicode_ci'
  ⇂ alter table `targets` add constraint `targets_user_id_foreign` foreign key (`user_id`) references `users` (`id`) on delete cascade
  2025_03_10_102306_create_reports_table .........................................................................
  ⇂ create table `reports` (`id` bigint unsigned not null auto_increment primary key, `user_id` bigint unsigned not null, `report_type` enum('monthly', 'yearly') not null, `report_file` varchar(255) not null, `created_at` timestamp null, `updated_at` timestamp null) default character set utf8mb4 collate 'utf8mb4_unicode_ci'
  ⇂ alter table `reports` add constraint `reports_user_id_foreign` foreign key (`user_id`) references `users` (`id`) on delete cascade
  2025_03_10_102324_create_scores_table .................................................................
  ⇂ create table `scores` (`id` bigint unsigned not null auto_increment primary key, `user_id` bigint unsigned not null, `score` enum('monthly', 'yearly') not null, `created_at` timestamp null, `updated_at` timestamp null) default character set utf8mb4 collate 'utf8mb4_unicode_ci'
  ⇂ alter table `scores` add constraint `scores_user_id_foreign` foreign key (`user_id`) references `users` (`id`) on delete cascade
  2025_03_10_102337_create_logs_table ............................................................................
  ⇂ create table `logs` (`id` bigint unsigned not null auto_increment primary key, `user_id` bigint unsigned not null, `action` varchar(255) not null, `details` text null, `created_at` timestamp null, `updated_at` timestamp null) default character set utf8mb4 collate 'utf8mb4_unicode_ci'
  ⇂ alter table `logs` add constraint `logs_user_id_foreign` foreign key (`user_id`) references `users` (`id`) on delete cascade
  2025_03_10_135934_create_personal_access_tokens_table ..........................................................
  ⇂ create table `personal_access_tokens` (`id` bigint unsigned not null auto_increment primary key, `tokenable_type` varchar(255) not null, `tokenable_id` bigint unsigned not null, `name` varchar(255) not null, `token` varchar(64) not null, `abilities` text null, `last_used_at` timestamp null, `expires_at` timestamp null, `created_at` timestamp null, `updated_at` timestamp null) default character set utf8mb4 collate 'utf8mb4_unicode_ci'
  ⇂ alter table `personal_access_tokens` add index `personal_access_tokens_tokenable_type_tokenable_id_index`(`tokenable_type`, `tokenable_id`)
  ⇂ alter table `personal_access_tokens` add unique `personal_access_tokens_token_unique`(`token`)

