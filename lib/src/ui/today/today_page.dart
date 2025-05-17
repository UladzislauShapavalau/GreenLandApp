// today_page.dart
import 'package:flutter/material.dart';
import 'package:greenland_app/src/data/plant.dart'; // Убедитесь, что класс Plant определен
import 'package:greenland_app/src/ui/today/today_date_widget.dart';
import 'package:greenland_app/src/ui/today/today_plant_widget.dart';
import 'package:greenland_app/src/config/styles/palette.dart'; // Если нужны цвета из палитры

class TodayPage extends StatefulWidget {
  const TodayPage({super.key});

  @override
  State<TodayPage> createState() => _TodayPageState();
}

class _TodayPageState extends State<TodayPage> {
  // Пример данных (в будущем это будет приходить из вашего источника данных)
  final List<Map<String, dynamic>> roomData = [
    {
      'roomName': 'Bedroom',
      'plants': [
        Plant(
          id: '1',
          image: 'assets/icons/plant_sweat.png',
          nickname: 'Gordon',
          name: 'Sweatheart',
          //currentWaterLevel: 3,
          //maxWaterLevel: 5,
          //task: 'Water'
        ),
        // Можно добавить еще растения для Bedroom
      ],
    },
    {
      'roomName': 'Kitchen',
      'plants': [
        Plant(
          id: '2',
          image: 'assets/images/plant_ficus.jpg',
          nickname: 'James',
          name: 'Ficus',
          //currentWaterLevel: 2,
          //maxWaterLevel: 5,
          //task: 'Fertilize'
        ), // Замените путь к картинке
        // Можно добавить еще растения для Kitchen
      ],
    },
  ];

  @override
  Widget build(BuildContext context) {
    return ListView(
      padding: const EdgeInsets.all(16.0),
      children: [
        const TodayDateWidget(),
        const SizedBox(height: 24.0),

        // Динамически создаем секции для каждой комнаты
        ...roomData.map((room) {
          return Column(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              _buildRoomHeader(room['roomName'] as String),
              const SizedBox(height: 16.0),
              ...(room['plants'] as List<Plant>).map((plant) {
                return Padding(
                  padding: const EdgeInsets.only(
                      bottom: 16.0), // Отступ между карточками растений
                  child: TodayPlantWidget(plant: plant),
                );
              }).toList(),
              const SizedBox(
                  height: 24.0), // Отступ после секции с растениями комнаты
            ],
          );
        }).toList(),
      ],
    );
  }

  // Вспомогательный виджет для заголовка комнаты
  Widget _buildRoomHeader(String roomName) {
    return Padding(
      padding: const EdgeInsets.symmetric(vertical: 8.0),
      child: Row(
        children: [
          Text(
            roomName,
            style: TextStyle(
              fontSize: 18,
              fontWeight: FontWeight.bold,
              color: Colors.grey[700],
            ),
          ),
          const SizedBox(width: 12),
          Expanded(
            child: Divider(
              thickness: 1,
              color: Colors.grey[300],
            ),
          ),
        ],
      ),
    );
  }
}
