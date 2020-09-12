
drop table if exists role_permission;

create table role_permission
(
    id            int unsigned auto_increment
        primary key,
    role_id       int unsigned default '0' not null,
    permission_id int          default 0   not null
);

INSERT INTO role_permission (id, role_id, permission_id) VALUES (80, 1, 9);
INSERT INTO role_permission (id, role_id, permission_id) VALUES (81, 1, 18);
INSERT INTO role_permission (id, role_id, permission_id) VALUES (82, 1, 17);
INSERT INTO role_permission (id, role_id, permission_id) VALUES (83, 1, 16);
INSERT INTO role_permission (id, role_id, permission_id) VALUES (84, 1, 15);
INSERT INTO role_permission (id, role_id, permission_id) VALUES (85, 1, 14);
INSERT INTO role_permission (id, role_id, permission_id) VALUES (86, 1, 13);
INSERT INTO role_permission (id, role_id, permission_id) VALUES (87, 1, 12);
INSERT INTO role_permission (id, role_id, permission_id) VALUES (88, 1, 11);
INSERT INTO role_permission (id, role_id, permission_id) VALUES (89, 1, 10);
INSERT INTO role_permission (id, role_id, permission_id) VALUES (90, 1, 4);
INSERT INTO role_permission (id, role_id, permission_id) VALUES (91, 1, 8);
INSERT INTO role_permission (id, role_id, permission_id) VALUES (92, 1, 7);
INSERT INTO role_permission (id, role_id, permission_id) VALUES (93, 1, 6);
INSERT INTO role_permission (id, role_id, permission_id) VALUES (94, 1, 5);
INSERT INTO role_permission (id, role_id, permission_id) VALUES (253, 2, 9);
INSERT INTO role_permission (id, role_id, permission_id) VALUES (254, 2, 18);
INSERT INTO role_permission (id, role_id, permission_id) VALUES (255, 2, 17);
INSERT INTO role_permission (id, role_id, permission_id) VALUES (256, 2, 16);
INSERT INTO role_permission (id, role_id, permission_id) VALUES (257, 2, 14);
INSERT INTO role_permission (id, role_id, permission_id) VALUES (258, 2, 13);
INSERT INTO role_permission (id, role_id, permission_id) VALUES (259, 2, 12);
INSERT INTO role_permission (id, role_id, permission_id) VALUES (260, 2, 11);
INSERT INTO role_permission (id, role_id, permission_id) VALUES (261, 2, 10);