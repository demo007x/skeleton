drop table if exists action_logs;
create table action_logs
(
    id         int auto_increment primary key,
    user_id    int          default 0                 not null,
    user_name  varchar(50)  default ''                not null,
    controller varchar(100) default ''                not null,
    action     varchar(100) default ''                not null,
    method     varchar(10)  default ''                not null,
    data       text                                   not null,
    url        varchar(255) default ''                not null,
    browser    varchar(100) default ''                not null,
    os         varchar(100) default ''                not null,
    ip         varchar(50)  default ''                not null,
    created_at timestamp    default CURRENT_TIMESTAMP not null,
    updated_at timestamp    default CURRENT_TIMESTAMP not null,
    deleted_at timestamp                              null
)
    comment '用户操作日志表';

create index user_id_index
    on action_logs (user_id);