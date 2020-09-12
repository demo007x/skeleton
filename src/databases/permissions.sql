
drop table if exists permissions;
create table permissions
(
    id          int unsigned auto_increment
        primary key,
    pid         int unsigned default '0'               not null,
    name        varchar(100) default ''                not null comment '权限名称',
    description varchar(100) default ''                not null comment '权限描述',
    controller  varchar(100) default ''                not null comment '控制器名称',
    action      varchar(100) default ''                not null comment 'action 名称',
    http_method varchar(10)  default ''                not null comment 'http请求方法',
    created_at  timestamp    default CURRENT_TIMESTAMP not null,
    updated_at  timestamp    default CURRENT_TIMESTAMP not null,
    deleted_at  timestamp                              null
)
    comment '权限表';

create index ctl_act_index
    on permissions (controller, action);

INSERT INTO permissions (id, pid, name, description, controller, action, http_method, created_at, updated_at, deleted_at) VALUES (4, 0, '权限功能', '', '', '', '', '2020-09-01 17:02:58', '2020-09-01 17:02:58', null);
INSERT INTO permissions (id, pid, name, description, controller, action, http_method, created_at, updated_at, deleted_at) VALUES (5, 4, '权限列表', '获取所有的权限', 'Permission', 'list', 'GET', '2020-09-01 17:03:18', '2020-09-01 17:03:18', null);
INSERT INTO permissions (id, pid, name, description, controller, action, http_method, created_at, updated_at, deleted_at) VALUES (6, 4, '权限添加', '添加权限功能的权限', 'Permission', 'create', 'POST', '2020-09-01 17:03:59', '2020-09-01 17:03:59', null);
INSERT INTO permissions (id, pid, name, description, controller, action, http_method, created_at, updated_at, deleted_at) VALUES (7, 4, '权限修改', '修改权限功能的权限', 'Permission', 'update', 'PUT', '2020-09-01 17:04:33', '2020-09-01 17:04:33', null);
INSERT INTO permissions (id, pid, name, description, controller, action, http_method, created_at, updated_at, deleted_at) VALUES (8, 4, '删除修改', '删除权限功能的权限', 'Permission', 'destroy', 'DELETE', '2020-09-01 17:07:42', '2020-09-07 15:49:16', null);
INSERT INTO permissions (id, pid, name, description, controller, action, http_method, created_at, updated_at, deleted_at) VALUES (9, 0, '后台用户管理', '管理后台用户管理的权限', '', '', '', '2020-09-04 09:12:52', '2020-09-04 10:13:31', null);
INSERT INTO permissions (id, pid, name, description, controller, action, http_method, created_at, updated_at, deleted_at) VALUES (10, 9, '管理员列表', '获取后台管理员列表', 'Administrator', 'list', 'GET', '2020-09-04 10:19:02', '2020-09-04 10:19:02', null);
INSERT INTO permissions (id, pid, name, description, controller, action, http_method, created_at, updated_at, deleted_at) VALUES (11, 9, '当前管理员权限', '获取当前管理员信息权限', 'Administrator', 'getCurrentAdministratorInfo', 'GET', '2020-09-04 10:20:19', '2020-09-04 10:20:19', null);
INSERT INTO permissions (id, pid, name, description, controller, action, http_method, created_at, updated_at, deleted_at) VALUES (12, 9, '获取某个管理员权限', '获取某个管理员权限', 'Administrator', 'getAdministratorInfo', 'GET', '2020-09-04 10:21:24', '2020-09-04 10:21:24', null);
INSERT INTO permissions (id, pid, name, description, controller, action, http_method, created_at, updated_at, deleted_at) VALUES (13, 9, '添加管理员权限', '添加管理员权限', 'Administrator', 'create', 'POST', '2020-09-04 10:22:03', '2020-09-04 10:22:03', null);
INSERT INTO permissions (id, pid, name, description, controller, action, http_method, created_at, updated_at, deleted_at) VALUES (14, 9, '更新管理员权限', '更新某一个管理员权限', 'Administrator', 'update', 'PUT', '2020-09-04 10:22:54', '2020-09-04 10:22:54', null);
INSERT INTO permissions (id, pid, name, description, controller, action, http_method, created_at, updated_at, deleted_at) VALUES (15, 9, '更新管理员密码', '更新|设置管理员密码权限', 'Administrator', 'updatePassword', 'PUT', '2020-09-04 10:26:02', '2020-09-04 10:26:30', null);
INSERT INTO permissions (id, pid, name, description, controller, action, http_method, created_at, updated_at, deleted_at) VALUES (16, 9, '禁止管理员权限', '设置管理员禁止登录权限', 'Administrator', 'forbid', 'GET', '2020-09-04 10:27:13', '2020-09-04 10:27:13', null);
INSERT INTO permissions (id, pid, name, description, controller, action, http_method, created_at, updated_at, deleted_at) VALUES (17, 9, '删除管理员权限', '删除管理员的权限', 'Administrator', 'destroy', 'GET', '2020-09-04 10:27:51', '2020-09-04 10:27:51', null);
INSERT INTO permissions (id, pid, name, description, controller, action, http_method, created_at, updated_at, deleted_at) VALUES (18, 9, '设置管理员角色权限', '设置管理员权限的权限', 'Administrator', 'setAdminRole', 'POST', '2020-09-04 10:29:15', '2020-09-04 10:29:15', null);
INSERT INTO permissions (id, pid, name, description, controller, action, http_method, created_at, updated_at, deleted_at) VALUES (19, 4, '获取权限列表(权限树)', '获取树形权限', 'Permission', 'tree', 'GET', '2020-09-07 17:55:48', '2020-09-07 17:55:48', null);