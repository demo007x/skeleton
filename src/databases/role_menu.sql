
drop table if exists role_menu;

create table role_menu
(
    id      int unsigned auto_increment
        primary key,
    role_id int unsigned default '0' not null,
    menu_id int unsigned default '0' not null
)
    comment '角色菜单表';

create index menu_id_index
    on role_menu (menu_id);

create index role_id_index
    on role_menu (role_id);

INSERT INTO role_menu (id, role_id, menu_id) VALUES (115, 1, 3);
INSERT INTO role_menu (id, role_id, menu_id) VALUES (116, 1, 7);
INSERT INTO role_menu (id, role_id, menu_id) VALUES (117, 1, 8);
INSERT INTO role_menu (id, role_id, menu_id) VALUES (118, 1, 5);
INSERT INTO role_menu (id, role_id, menu_id) VALUES (119, 1, 4);
INSERT INTO role_menu (id, role_id, menu_id) VALUES (192, 2, 3);
INSERT INTO role_menu (id, role_id, menu_id) VALUES (193, 2, 4);
INSERT INTO role_menu (id, role_id, menu_id) VALUES (194, 2, 5);
INSERT INTO role_menu (id, role_id, menu_id) VALUES (195, 2, 7);
INSERT INTO role_menu (id, role_id, menu_id) VALUES (196, 2, 8);