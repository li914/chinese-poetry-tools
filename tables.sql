create table shi(
id int(10) auto_increment primary key ,
shi_id char(16)  not null default '',
title varchar(250) not null default '',
author varchar(150) not null default '',
dynasty char(2) not null default 'T' comment '词所属朝代（S-宋代, T-唐代）,默认T',
tags varchar(80) not null default '',
content text not null default '',
strains text not null default '',
create_time int(10) not null default 0,
update_time int(10) not null default 0
);


create table shi_author(
id int(10) auto_increment primary key ,
author varchar(250) not null default '',
dynasty char(2) not null default 'T' comment '诗人所属朝代（S-宋代, T-唐代）,默认T',
intro text not null default '',
other text not null default '' comment '其它，以后可能需要的备注',
create_time int(10) not null default 0,
update_time int(10) not null default 0
);


create table ci(
id int(10) auto_increment primary key ,
ci_id char(16)  not null default '',
title varchar(250) not null default '',
author varchar(150) not null default '',
dynasty char(2) not null default 'S' comment '词所属朝代（S-宋代）,默认T',
tags varchar(80) not null default '',
content text not null default '',
rhythmic varchar(255) not null default '',
create_time int(10) not null default 0,
update_time int(10) not null default 0
);

create table ci_author(
id int(10) auto_increment primary key ,
author varchar(250) not null default '',
dynasty char(2) not null default 'T' comment '诗人所属朝代（S-宋代）,默认T',
intro text not null  default '',
short_intro text not null default '',
other text not null default '' comment '其它，以后可能需要的备注',
create_time int(10) not null default 0,
update_time int(10) not null default 0
);

create table shi_jing(
id int(10) auto_increment primary key ,
shijing_id char(16)  not null default '',
title varchar(120) not null default '',
chapter varchar(120) not null default '',
section varchar(120) not null default '',
content text not null default '',
create_time int(10) not null default 0,
update_time int(10) not null default 0
);