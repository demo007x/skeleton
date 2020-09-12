
drop table if exists administrators;
create table administrators
(
    id           int unsigned auto_increment
        primary key,
    name         varchar(25)  default ''                not null,
    password     varchar(100) default ''                not null,
    email        varchar(100) default ''                not null,
    mobile       char(11)     default ''                not null,
    gender       tinyint(1)   default 0                 not null comment '0:未知,1:男,2:女',
    introduction varchar(255) default ''                not null,
    status       tinyint      default 1                 not null comment '0:正常用户,1:封禁用户',
    login_at     timestamp                              null,
    ip           int unsigned                           null,
    avatar       varchar(255) default ''                not null,
    created_at   timestamp    default CURRENT_TIMESTAMP not null,
    updated_at   timestamp    default CURRENT_TIMESTAMP not null,
    deleted_at   timestamp                              null
);

create index email_index
    on administrators (email);

INSERT INTO `administrators` (`id`, `name`, `password`, `email`, `mobile`, `gender`, `introduction`, `status`, `login_at`, `ip`, `avatar`, `created_at`, `updated_at`, `deleted_at`) VALUES
('1', 'admin', '$2y$10$wEJwt6TLEe65nwFZXTU6z.E4X05d/g.TAEE2SZb16YVMbURxPVmfG', 'admin@admin.com', '', '1', 'admin', '1', '2020-09-10 16:00:22', '0', 'https://wpimg.wallstcn.com/f778738c-e4f8-4870-b634-56703b4acafe.gif', '2020-08-12 01:31:21', '2020-09-10 16:00:22', NULL);