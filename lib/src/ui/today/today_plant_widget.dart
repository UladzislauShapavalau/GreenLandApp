// today_plant_widget.dart
import 'package:flutter/material.dart';
import 'package:greenland_app/src/data/plant.dart';
import 'package:greenland_app/src/config/styles/palette.dart'; // Для цветов

class TodayPlantWidget extends StatelessWidget {
  const TodayPlantWidget({required this.plant, super.key});

  final Plant plant;

  @override
  Widget build(BuildContext context) {
    // Определяем, какая кнопка будет отображаться (Done или Cancel)
    // Для примера, если задача "Water", то кнопка "Done". Для других - "Cancel".
    // Вы можете усложнить эту логику.
    //bool isWaterTask = plant.task.toLowerCase() == 'water';
    String buttonText = 'Done'; // Или может быть 'Skip'
    final Color buttonColor =
        Palette.navBarColor ?? Colors.green; // Всегда основной цвет
    final Color buttonTextColor = Colors.white; // Всегда белый текст
    final BorderSide buttonBorder =
        BorderSide.none; // Без рамки для основного стиля кнопки

    return Container(
      // width: 350, // Убираем фиксированную ширину
      decoration: BoxDecoration(
        color: Colors.white,
        borderRadius: BorderRadius.circular(16.0), // Более скругленные углы
        boxShadow: [
          BoxShadow(
            color: Colors.grey.withOpacity(0.15),
            spreadRadius: 1,
            blurRadius: 8,
            offset: Offset(0, 4), // Тень для объема
          ),
        ],
      ),
      child: Padding(
        padding: const EdgeInsets.all(16.0),
        child: Row(
          crossAxisAlignment:
              CrossAxisAlignment.start, // Выравниваем по центру вертикально
          children: [
            // Блок с изображением
            SizedBox(
              width: 160, // Фиксированная ширина для единообразия
              height: 160, // Фиксированная высота
              child: Stack(
                alignment: Alignment.center,
                children: [
                  DecoratedBox(
                    decoration: BoxDecoration(
                      color: Colors.grey.shade200, // Фон подложки
                      borderRadius:
                          BorderRadius.circular(35.0), // Скругление подложки
                    ),
                    child: SizedBox.expand(), // Растягиваем на весь SizedBox
                  ),
                  ClipRRect(
                    borderRadius: BorderRadius.circular(
                        8.0), // Скругление самого изображения
                    child: Image.asset(
                      // Используем plant.image
                      plant.image.isNotEmpty
                          ? plant.image
                          : 'assets/images/plant_placeholder.png', // Заглушка, если нет изображения
                      width: 130, // Размер изображения чуть меньше подложки
                      height: 130,
                      fit: BoxFit.cover,
                      errorBuilder: (context, error, stackTrace) {
                        return Image.asset(
                            'assets/images/plant_placeholder.png',
                            width: 130,
                            height: 130,
                            fit: BoxFit.cover); // Заглушка при ошибке загрузки
                      },
                    ),
                  ),
                ],
              ),
            ),
            const SizedBox(width: 16), // Отступ между изображением и текстом

            // Блок с информацией
            Expanded(
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                mainAxisAlignment:
                    MainAxisAlignment.start, // Центрируем контент колонки
                children: [
                  const SizedBox(height: 4.0),
                  Text(
                    plant.nickname,
                    style: const TextStyle(
                      fontSize: 20.0, // Размер поменьше для мобильных
                      fontWeight: FontWeight.bold,
                    ),
                    maxLines: 1,
                    overflow: TextOverflow.ellipsis,
                  ),
                  Text(
                    plant.name,
                    style: TextStyle(
                      fontSize: 14.0, // Размер поменьше
                      color: Colors.grey[600],
                    ),
                    maxLines: 1,
                    overflow: TextOverflow.ellipsis,
                  ),
                  const SizedBox(height: 8.0),
                  // Иконки капель для полива
                  // Row(
                  //   children: List.generate(plant.maxWaterLevel, (index) {
                  //     return Icon(
                  //       Icons.water_drop,
                  //       size: 18, // Размер капель
                  //       color: index < plant.currentWaterLevel
                  //           ? (Palette.navBarColor ?? Colors.blue) // Цвет заполненных капель
                  //           : Colors.grey[300], // Цвет пустых капель
                  //     );
                  //   }),
                  // ),
                  const SizedBox(height: 12.0),
                  // Кнопка действия
                  Align(
                    alignment: Alignment.centerRight, // Кнопка справа
                    child: SizedBox(
                      height: 36, // Высота кнопки
                      child: ElevatedButton(
                        onPressed: () {
                          // TODO: Добавить логику для кнопки (полить, отменить и т.д.)
                          print('${plant.nickname}: ${buttonText} pressed');
                        },
                        style: ElevatedButton.styleFrom(
                          backgroundColor: buttonColor,
                          foregroundColor: buttonTextColor,
                          elevation: 0,
                          padding: const EdgeInsets.symmetric(
                              horizontal: 20, vertical: 8),
                          shape: RoundedRectangleBorder(
                            borderRadius: BorderRadius.circular(
                                20.0), // Более круглая кнопка
                            side: buttonBorder,
                          ),
                        ),
                        child: Text(buttonText),
                      ),
                    ),
                  ),
                ],
              ),
            ),
          ],
        ),
      ),
    );
  }
}
