create table todos (
  id int not null auto_increment primary key,
  state tinyint(1) default 0, /* 0:not finished, 1:finished */
  title text
);

insert into todos (state, title) values
(0, 'todo 0'),
(0, 'todo 1'),
(1, 'todo 2');