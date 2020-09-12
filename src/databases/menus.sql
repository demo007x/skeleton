
drop table if exists menus;
create table menus
(
    id         int auto_increment
        primary key,
    pid        int unsigned     default '0'               not null comment '父级菜单id',
    name       varchar(100)     default ''                not null,
    title      varchar(100)     default ''                not null comment '菜单名称',
    icon       varchar(100)     default ''                not null comment '菜单图标',
    path       varchar(100)     default ''                not null comment '菜单路径',
    component  varchar(255)     default ''                not null comment '组件地址',
    is_cache   tinyint(1)       default 0                 null comment '是否缓存',
    sort       tinyint unsigned default '1'               not null comment '排序',
    created_at timestamp        default CURRENT_TIMESTAMP not null,
    updated_at timestamp        default CURRENT_TIMESTAMP not null,
    deleted_at timestamp                                  null
)
    comment '菜单表';

INSERT INTO menus (id, pid, name, title, icon, path, component, is_cache, sort, created_at, updated_at, deleted_at) VALUES (3, 0, 'SystemManager', '系统管理', 'el-icon-setting', 'systemManager', 'Layout', 0, 1, '2020-08-27 08:22:21', '2020-08-30 11:06:52', null);
INSERT INTO menus (id, pid, name, title, icon, path, component, is_cache, sort, created_at, updated_at, deleted_at) VALUES (4, 3, 'Administrator', '后台用户管理', 'people', 'administrator', 'systemManager/administrator', 1, 4, '2020-08-27 08:23:04', '2020-08-31 21:55:24', null);
INSERT INTO menus (id, pid, name, title, icon, path, component, is_cache, sort, created_at, updated_at, deleted_at) VALUES (5, 3, 'Roles', '角色管理', 'peoples', 'roles', 'systemManager/roles', 0, 3, '2020-08-27 08:23:57', '2020-08-27 08:23:57', null);
INSERT INTO menus (id, pid, name, title, icon, path, component, is_cache, sort, created_at, updated_at, deleted_at) VALUES (7, 3, 'Menu', '菜单管理', 'tree-table', 'menu', 'systemManager/menu', 0, 1, '2020-08-28 05:48:34', '2020-08-28 05:48:34', null);
INSERT INTO menus (id, pid, name, title, icon, path, component, is_cache, sort, created_at, updated_at, deleted_at) VALUES (8, 3, 'Permission', '权限管理', 'lock', 'permission', 'systemManager/permission', 1, 1, '2020-08-30 11:10:59', '2020-09-07 21:10:59', null);