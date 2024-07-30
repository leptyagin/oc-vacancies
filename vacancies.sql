--
-- Структура таблицы `oc_vacancy`
--

CREATE TABLE `oc_vacancy` (
  `id` int NOT NULL,
  `title` text COLLATE utf8mb4_general_ci NOT NULL,
  `description` text COLLATE utf8mb4_general_ci,
  `sort_order` int DEFAULT NULL,
  `status` tinyint(1) DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


--
-- Структура таблицы `oc_vacancy_requirements`
--

CREATE TABLE `oc_vacancy_requirements` (
  `id` int NOT NULL,
  `vacancy_id` int NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


--
-- Индексы таблицы `oc_vacancy`
--
ALTER TABLE `oc_vacancy`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `oc_vacancy_requirements`
--
ALTER TABLE `oc_vacancy_requirements`
  ADD PRIMARY KEY (`id`),
  ADD KEY `vacancy_id` (`vacancy_id`);

--
-- AUTO_INCREMENT для таблицы `oc_vacancy`
--
ALTER TABLE `oc_vacancy`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT для таблицы `oc_vacancy_requirements`
--
ALTER TABLE `oc_vacancy_requirements`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=42;

--
-- Ограничения внешнего ключа таблицы `oc_vacancy_requirements`
--
ALTER TABLE `oc_vacancy_requirements`
  ADD CONSTRAINT `oc_vacancy_requirements_ibfk_1` FOREIGN KEY (`vacancy_id`) REFERENCES `oc_vacancy` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;
COMMIT;
