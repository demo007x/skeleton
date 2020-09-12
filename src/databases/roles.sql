
drop table if exists roles;
create table roles
(
    id          int unsigned auto_increment
        primary key,
    role_key    varchar(25)  default ''                not null,
    name        varchar(25)  default ''                not null,
    description varchar(100) default ''                not null,
    created_at  timestamp    default CURRENT_TIMESTAMP not null,
    updated_at  timestamp    default CURRENT_TIMESTAMP not null,
    deleted_at  timestamp                              null
);

INSERT INTO roles (id, role_key, name, description, created_at, updated_at, deleted_at) VALUES (1, 'admin', 'admin', '创世角色', '2020-08-25 18:17:18', '2020-08-27 15:01:27', null);
INSERT INTO roles (id, role_key, name, description, created_at, updated_at, deleted_at) VALUES (2, 'guster', 'guster', '游客模式', '2020-08-30 14:35:27', '2020-08-30 15:31:03', null);