import 'package:flutter/material.dart';
import 'package:greenland_app/src/data/plant.dart';
import 'package:greenland_app/src/ui/add_plant/add_reminder_widget.dart';

import 'add_plant_widget.dart';

class AddPlantPage extends StatefulWidget {
  const AddPlantPage({super.key});

  @override
  State<AddPlantPage> createState() => _AddPlantPageState();
}

class _AddPlantPageState extends State<AddPlantPage> {
  void _onPlantAdded() {
    // Handle the logic after a plant is added successfully
    // For example, you can navigate to another page or show a success message
    ScaffoldMessenger.of(context).showSnackBar(
      const SnackBar(content: Text('Plant added successfully')),
    );
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: const Text('New Plant'),
        leading: IconButton(
          icon: Icon(
              Icons.adaptive.arrow_back), // Используем адаптивную иконку назад
          onPressed: () => Navigator.of(context).pop(),
        ),
      ),
      body: SingleChildScrollView(
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            const Row(
              children: [
                SizedBox(width: 30),
                Text(
                  'New plant',
                  style: TextStyle(
                    fontSize: 20.0,
                    fontWeight: FontWeight.bold,
                  ),
                ),
                SizedBox(width: 8),
              ],
            ),
            Row(
              children: [
                AddPlantWidget(onPlantAdded: _onPlantAdded),
              ],
            ),
            const SizedBox(height: 20),
            const Row(
              children: [
                SizedBox(width: 30),
                Text(
                  'Reminders',
                  style: TextStyle(
                    fontSize: 20.0,
                    fontWeight: FontWeight.bold,
                  ),
                ),
                Expanded(
                  child: Divider(
                    thickness: 1,
                    indent: 10,
                    endIndent: 50,
                    color: Colors.black,
                  ),
                ),
              ],
            ),
            AddReminderWidget(
              plant: Plant(
                  id: '', image: '', nickname: 'Gordon', name: 'Sweatheart'),
            ),
          ],
        ),
      ),
    );
  }
}
