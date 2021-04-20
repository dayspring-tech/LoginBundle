create table if not exists users
(
	id int auto_increment
		primary key,
	email varchar(100) null,
	password varchar(100) null,
	salt varchar(100) null,
	reset_token char(40) null,
	reset_token_expire datetime null,
	created_date datetime null,
	last_login_date datetime null,
	is_active tinyint(1) default 1 not null,
	constraint user_username_UNIQUE
		unique (email)
);

create table if not exists roles
(
	id int auto_increment
		primary key,
	role_name varchar(50) not null
);

create table if not exists roles_users
(
	user_id int not null,
	role_id int not null,
	primary key (user_id, role_id),
	constraint FK_rolesUsers_role
		foreign key (role_id) references roles (id),
	constraint FK_rolesUsers_user
		foreign key (user_id) references users (id)
);


delete from roles_users;
delete from roles;
delete from users;
