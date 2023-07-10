SET time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;


CREATE TABLE treeTable (
  id int(11) NOT NULL,
  parent_id varchar(2) DEFAULT '0',
  name varchar(66) NOT NULL DEFAULT current_timestamp(),
  description varchar(160) DEFAULT NULL
) ;

INSERT INTO treeTable (id, parent_id, name, description) VALUES(1, '0', 'Растения', '');
INSERT INTO treeTable (id, parent_id, name, description) VALUES(2, '0', 'Животные', '');
INSERT INTO treeTable (id, parent_id, name, description) VALUES(3, '0', 'Механизмы', '');
INSERT INTO treeTable (id, parent_id, name, description) VALUES(4, '0', 'Планеты', '');
INSERT INTO treeTable (id, parent_id, name, description) VALUES(5, '1', 'Голосеменные', '');
INSERT INTO treeTable (id, parent_id, name, description) VALUES(6, '1', 'Розоцветные', '');
INSERT INTO treeTable (id, parent_id, name, description) VALUES(7, '1', 'Крестоцветные', '');
INSERT INTO treeTable (id, parent_id, name, description) VALUES(8, '2', 'Бактерии', '');
INSERT INTO treeTable (id, parent_id, name, description) VALUES(9, '2', 'Рыбовы', '');
INSERT INTO treeTable (id, parent_id, name, description) VALUES(10, '3', 'Млекопитающие', '');
INSERT INTO treeTable (id, parent_id, name, description) VALUES(11, '10', 'Сумчатые', '');
INSERT INTO treeTable (id, parent_id, name, description) VALUES(12, '11', 'Кенгуру', 'Скачущее невесть что');
INSERT INTO treeTable (id, parent_id, name, description) VALUES(13, '11', 'Коала', 'Лазает, эвкалипт поедает. Бестолковое.');
INSERT INTO treeTable (id, parent_id, name, description) VALUES(14, '5', 'Сосна', '');
INSERT INTO treeTable (id, parent_id, name, description) VALUES(15, '5', 'Ель', '');
INSERT INTO treeTable (id, parent_id, name, description) VALUES(16, '6', 'Шиповник', '');
INSERT INTO treeTable (id, parent_id, name, description) VALUES(17, '6', 'Лютик', '');
INSERT INTO treeTable (id, parent_id, name, description) VALUES(18, '6', 'Роза', '');
INSERT INTO treeTable (id, parent_id, name, description) VALUES(19, '7', 'Хрен', '');
INSERT INTO treeTable (id, parent_id, name, description) VALUES(20, '7', 'Капуста', '');
INSERT INTO treeTable (id, parent_id, name, description) VALUES(21, '7', 'Редис', '');
INSERT INTO treeTable (id, parent_id, name, description) VALUES(22, '4', 'Нептун', 'предпоследняя в СС');
INSERT INTO treeTable (id, parent_id, name, description) VALUES(23, '4', 'Меркурий', 'Слишком горяч для жизни');
INSERT INTO treeTable (id, parent_id, name, description) VALUES(24, '4', 'Замля', 'Более-менее жить можно');
INSERT INTO treeTable (id, parent_id, name, description) VALUES(25, '8', 'Эшериция Коли', 'Просто кишечная палочка. повсеместный вид');
INSERT INTO treeTable (id, parent_id, name, description) VALUES(26, '8', 'Азотобактер', 'Почвенный микроб. Полезный');
INSERT INTO treeTable (id, parent_id, name, description) VALUES(27, '8', 'Микоплазма', 'Вызывает бронхиты');
INSERT INTO treeTable (id, parent_id, name, description) VALUES(28, '9', 'Щука', '');
INSERT INTO treeTable (id, parent_id, name, description) VALUES(29, '9', 'Язь', 'рыба моей мечты');
INSERT INTO treeTable (id, parent_id, name, description) VALUES(30, '9', 'Акула', '');
INSERT INTO treeTable (id, parent_id, name, description) VALUES(31, '3', 'Часы', '');
INSERT INTO treeTable (id, parent_id, name, description) VALUES(32, '3', 'Весы', '');
INSERT INTO treeTable (id, parent_id, name, description) VALUES(33, '3', 'Электровеник', '');
INSERT INTO treeTable (id, parent_id, name, description) VALUES(34, '6', 'Малина', 'Вот как ни удивит кого - малина тоже розоцветные.');

CREATE TABLE users (
  id int(3) NOT NULL,
  login varchar(22) DEFAULT current_timestamp(),
  password varchar(64) NOT NULL
) ;

INSERT INTO users (id, login, password) VALUES(1, 'zema', '$2y$10$bsklelMdsnZnNFkBDANgSO13lnFWPCRvzgQwjesNYDmBx2v15LGsG');
INSERT INTO users (id, login, password) VALUES(2, 'Volkov', '$2y$10$YX5nunWUnu4pzTNLE9iNSuv00EteEUWZkG2RXW3IrROW4vilbEiEG');
INSERT INTO users (id, login, password) VALUES(5, 'Cardigan', '$2y$10$CtmMu9MUKjWHQpZlAHdfkuwsUf1lV996ffxhs4XgfSOLWTeQOO4Mm');
INSERT INTO users (id, login, password) VALUES(6, 'yozki', '$2y$10$zeMapzBoYisUBVxsHR0QgOwd5qtWD4Uyr7uV0VhUEgVjctzLRgPK2');


ALTER TABLE treeTable
  ADD PRIMARY KEY (id);

ALTER TABLE users
  ADD PRIMARY KEY (id),
  ADD UNIQUE KEY login (login);


ALTER TABLE treeTable
  MODIFY id int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE users
  MODIFY id int(3) NOT NULL AUTO_INCREMENT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
