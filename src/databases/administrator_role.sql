drop table if exists administrator_role;
create table administrator_role
(
    id               int unsigned auto_increment
        primary key,
    administrator_id int default 0 not null,
    role_id          int default 0 not null
);

create index administrators_id_index
    on administrator_role (administrator_id);

create index roles_id_index
    on administrator_role (role_id);

INSERT INTO administrator_role (id, administrator_id, role_id) VALUES (56, 1, 1);
INSERT INTO administrator_role (id, administrator_id, role_id) VALUES (62, 4, 2);