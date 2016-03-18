INSERT INTO `roles`
(`id`, `role_name`)
VALUES
(1, 'ROLE_User');

INSERT INTO `users`
(`id`, `email`, `password`, `salt`)
VALUES
(1, 'testuser@example.com', '$2y$12$nOQ1p5XXnnFCOn5NEC8B3ez05hYSuOq1ka9SrMbxNpKZF8/BjiamG', null);

INSERT INTO `roles_users`
(`user_id`, `role_id`)
VALUES
(1, 1);