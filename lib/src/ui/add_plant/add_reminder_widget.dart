import 'dart:io';

import 'package:flutter/foundation.dart';
import 'package:flutter/material.dart';
import 'package:greenland_app/src/data/plant.dart';
import 'package:image_picker/image_picker.dart';
import 'package:greenland_app/src/config/styles/palette.dart';
import 'dart:typed_data';

class AddReminderWidget extends StatefulWidget {
  const AddReminderWidget({required this.plant, super.key});

  final Plant plant;

  @override
  _AddReminderWidgetState createState() => _AddReminderWidgetState();
}

class _AddReminderWidgetState extends State<AddReminderWidget> {
  TextEditingController _dateController = TextEditingController();
  int? _selectedCycle;
  Future<void> _selectDate(BuildContext context) async {
    final DateTime? picked = await showDatePicker(
      context: context,
      initialDate: DateTime.now(),
      firstDate: DateTime(2000),
      lastDate: DateTime(2030),
    );
    if (picked != null) {
      setState(() {
        _dateController.text = "${picked.toLocal()}".split(' ')[0];
      });
    }
  }

  @override
  Widget build(BuildContext context) {
    return SizedBox(
      width: 400,
      height: 320,
      child: Padding(
          padding: const EdgeInsets.fromLTRB(30.0, 16.0, 16.0, 16.0),
          child: Column(
            children: [
              SizedBox(
                height: 16,
              ),
              DropdownButtonFormField<String>(
                decoration: InputDecoration(
                  labelText: 'Type',
                  border: OutlineInputBorder(),
                ),
                items: <String>[
                  'Water',
                  'Rotate',
                ].map<DropdownMenuItem<String>>((String value) {
                  return DropdownMenuItem<String>(
                    value: value,
                    child: Text(value),
                  );
                }).toList(),
                onChanged: (String? newValue) {
                  // Handle change
                },
              ),
              SizedBox(
                height: 16,
              ),
              TextFormField(
                controller: _dateController,
                decoration: InputDecoration(
                  labelText: 'Start execution',
                  border: OutlineInputBorder(),
                  suffixIcon: Icon(Icons.calendar_today),
                ),
                readOnly: true,
                onTap: () => _selectDate(context),
              ),
              SizedBox(
                height: 16,
              ),
              DropdownButtonFormField<int>(
                menuMaxHeight: 250,
                decoration: InputDecoration(
                  labelText: 'Cycle',
                  border: OutlineInputBorder(),
                ),
                value: _selectedCycle,
                items: List.generate(60, (index) => index + 1)
                    .map<DropdownMenuItem<int>>((int value) {
                  return DropdownMenuItem<int>(
                    value: value,
                    child: Text('$value Days'),
                  );
                }).toList(),
                onChanged: (int? newValue) {
                  setState(() {
                    _selectedCycle = newValue;
                  });
                },
              ),
              SizedBox(height: 16.0),
              Align(
                alignment: Alignment.centerRight,
                child: ElevatedButton(
                  style: ButtonStyle(
                    backgroundColor:
                        MaterialStateProperty.all(Palette.doneColor),
                    foregroundColor:
                        MaterialStateProperty.all(Palette.complete),
                  ),
                  onPressed: () {
                    // Handle save
                  },
                  child: Text('Save'),
                ),
              ),
            ],
          )),
    );
  }
}
