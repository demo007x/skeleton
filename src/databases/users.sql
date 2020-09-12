
drop table if exists users;

create table users
(
    id         int unsigned auto_increment  primary key,
    name       varchar(25)      default ''                not null,
    email      varchar(100)     default ''                not null,
    password   varchar(100)     default ''                not null,
    gender     tinyint unsigned default '0'               not null comment '性别(0:未知,1:男,2:女)',
    avatar     varchar(255)     default ''                not null,
    mobile     char(11)         default ''                not null,
    created_at timestamp        default CURRENT_TIMESTAMP not null,
    updated_at timestamp        default CURRENT_TIMESTAMP not null,
    deleted_at timestamp                                  null,
    constraint email
        unique (email),
    constraint mobile
        unique (mobile)
);

create index email_index on users (email);

