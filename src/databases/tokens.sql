
drop table if exists tokens;

create table tokens
(
    id     int auto_increment primary key,
    token  varchar(255) default ''  not null,
    expire int unsigned default '0' not null
)
    comment 'token 表';

create index token_index on tokens (token);

