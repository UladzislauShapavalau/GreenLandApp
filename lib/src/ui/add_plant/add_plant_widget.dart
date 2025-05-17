import 'dart:io';
import 'package:flutter/foundation.dart';
import 'package:flutter/material.dart';
import 'package:image_picker/image_picker.dart';
import 'package:greenland_app/src/config/styles/palette.dart';
import 'dart:typed_data';
import 'dart:convert';
import 'package:http/http.dart' as http;
import 'package:shared_preferences/shared_preferences.dart';

class AddPlantWidget extends StatefulWidget {
  const AddPlantWidget({required this.onPlantAdded, super.key});

  final VoidCallback onPlantAdded;

  @override
  _AddPlantWidgetState createState() => _AddPlantWidgetState();
}

class _AddPlantWidgetState extends State<AddPlantWidget> {
  final _formKey = GlobalKey<FormState>();
  final TextEditingController _dateController = TextEditingController();
  Uint8List? webImage;
  File? _pickedImage;

  String? _nickname, _name, _location, _type, _description;

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

  Future<void> _pickImage() async {
    final ImagePicker _picker = ImagePicker();
    try {
      if (kIsWeb) {
        XFile? image = await _picker.pickImage(source: ImageSource.gallery);
        if (image != null) {
          var f = await image.readAsBytes();
          setState(() {
            webImage = f;
            _pickedImage = File('a'); // Dummy file as placeholder
          });
        } else {
          print('No image has been picked');
        }
      } else {
        XFile? image = await _picker.pickImage(source: ImageSource.gallery);
        if (image != null) {
          setState(() {
            _pickedImage = File(image.path);
          });
        } else {
          print('No image has been picked');
        }
      }
    } catch (e) {
      print('Error picking image: $e');
    }
  }

  Future<String?> _fetchUuid(String name, String endpoint) async {
    final prefs = await SharedPreferences.getInstance();
    final token = prefs.getString('auth_token');

    final url = 'http://10.0.2.2:8000/api/$endpoint';
    final headers = {
      HttpHeaders.contentTypeHeader: ContentType.json.toString(),
      HttpHeaders.authorizationHeader: 'Bearer $token'
    };
    final body = json.encode({'name': name});

    try {
      final response =
          await http.post(Uri.parse(url), headers: headers, body: body);

      if (response.statusCode == 200) {
        final responseData = json.decode(response.body);
        return responseData['id'];
      } else {
        print('Failed to fetch UUID from $endpoint: ${response.body}');
      }
    } catch (error) {
      print('Error fetching UUID from $endpoint: $error');
    }
    return null;
  }

  Future<void> _savePlant() async {
    if (_formKey.currentState!.validate()) {
      _formKey.currentState!.save();

      // Fetch location UUID
      final locationUuid = await _fetchUuid(_location!, 'locations');
      if (locationUuid == null) {
        print('Invalid location');
        return;
      }

      // Fetch type UUID
      final typeUuid = await _fetchUuid(_type!, 'plant-types');
      if (typeUuid == null) {
        print('Invalid type');
        return;
      }

      // Convert image to base64
      String? base64Image;
      if (webImage != null) {
        base64Image = base64Encode(webImage!);
      } else if (_pickedImage != null) {
        base64Image = base64Encode(await _pickedImage!.readAsBytes());
      }

      // Get auth token from SharedPreferences
      final prefs = await SharedPreferences.getInstance();
      final token = prefs.getString('auth_token');

      final url = 'http://10.0.2.2:8000/api/plants';
      final headers = {
        HttpHeaders.contentTypeHeader: ContentType.json.toString(),
        HttpHeaders.authorizationHeader: 'Bearer $token'
      };
      final body = json.encode({
        'name': _name,
        'nickname': _nickname,
        'adoptionDate': _dateController.text,
        'description': _description,
        'image': base64Image,
        'location': locationUuid,
        'type': typeUuid,
      });

      try {
        final response =
            await http.post(Uri.parse(url), headers: headers, body: body);

        if (response.statusCode == 200) {
          // Handle successful plant addition
          print('Plant added successfully');
          widget.onPlantAdded();
        } else {
          // Handle addition error
          print('Failed to add plant: ${response.body}');
        }
      } catch (error) {
        // Handle any errors
        print('Error: $error');
      }
    }
  }

  @override
  Widget build(BuildContext context) {
    return SizedBox(
      width: 400,
      height: 760,
      child: Padding(
        padding: const EdgeInsets.fromLTRB(30.0, 16.0, 16.0, 16.0),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.center,
          children: [
            InkWell(
              onTap: _pickImage,
              child: SizedBox(
                width: 200,
                height: 200,
                child: DecoratedBox(
                  decoration: BoxDecoration(
                    border: Border.all(
                        color: Colors.grey, style: BorderStyle.solid),
                    borderRadius: BorderRadius.circular(35.0),
                  ),
                  child: _pickedImage == null
                      ? Center(
                          child: Text(
                            'Browse your file\nJPG, PNG (Max 250x250px - 2Mb)',
                            textAlign: TextAlign.center,
                            style: TextStyle(color: Colors.grey),
                          ),
                        )
                      : kIsWeb
                          ? ClipRRect(
                              borderRadius: BorderRadius.circular(35.0),
                              child: Image.memory(webImage!, fit: BoxFit.cover),
                            )
                          : ClipRRect(
                              borderRadius: BorderRadius.circular(35.0),
                              child:
                                  Image.file(_pickedImage!, fit: BoxFit.cover),
                            ),
                ),
              ),
            ),
            SizedBox(width: 16.0),
            Expanded(
              child: Form(
                key: _formKey,
                child: Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    TextFormField(
                      decoration: InputDecoration(
                        labelText: 'Nickname',
                        border: OutlineInputBorder(),
                      ),
                      validator: (value) {
                        if (value == null || value.isEmpty) {
                          return 'Please enter a nickname';
                        }
                        return null;
                      },
                      onSaved: (value) {
                        _nickname = value;
                      },
                    ),
                    SizedBox(height: 16.0),
                    TextFormField(
                      decoration: InputDecoration(
                        labelText: 'Name',
                        border: OutlineInputBorder(),
                      ),
                      validator: (value) {
                        if (value == null || value.isEmpty) {
                          return 'Please enter a name';
                        }
                        return null;
                      },
                      onSaved: (value) {
                        _name = value;
                      },
                    ),
                    SizedBox(height: 16.0),
                    TextFormField(
                      controller: _dateController,
                      decoration: InputDecoration(
                        labelText: 'Adoption date',
                        border: OutlineInputBorder(),
                        suffixIcon: Icon(Icons.calendar_today),
                      ),
                      readOnly: true,
                      onTap: () => _selectDate(context),
                      validator: (value) {
                        if (value == null || value.isEmpty) {
                          return 'Please select an adoption date';
                        }
                        return null;
                      },
                    ),
                    SizedBox(height: 16.0),
                    DropdownButtonFormField<String>(
                      decoration: InputDecoration(
                        labelText: 'Location',
                        border: OutlineInputBorder(),
                      ),
                      items: <String>[
                        'Bathroom',
                        'Outdoor',
                        'Balcony',
                        'Garden'
                      ].map<DropdownMenuItem<String>>((String value) {
                        return DropdownMenuItem<String>(
                          value: value,
                          child: Text(value),
                        );
                      }).toList(),
                      onChanged: (String? newValue) {
                        setState(() {
                          _location = newValue;
                        });
                      },
                      validator: (value) {
                        if (value == null || value.isEmpty) {
                          return 'Please select a location';
                        }
                        return null;
                      },
                      onSaved: (value) {
                        _location = value;
                      },
                    ),
                    SizedBox(height: 16.0),
                    DropdownButtonFormField<String>(
                      decoration: InputDecoration(
                        labelText: 'Type',
                        border: OutlineInputBorder(),
                      ),
                      items: <String>['Succulent', 'Fern', 'Cactus', 'Flower']
                          .map<DropdownMenuItem<String>>((String value) {
                        return DropdownMenuItem<String>(
                          value: value,
                          child: Text(value),
                        );
                      }).toList(),
                      onChanged: (String? newValue) {
                        setState(() {
                          _type = newValue;
                        });
                      },
                      validator: (value) {
                        if (value == null || value.isEmpty) {
                          return 'Please select a type';
                        }
                        return null;
                      },
                      onSaved: (value) {
                        _type = value;
                      },
                    ),
                    SizedBox(height: 16.0),
                    TextFormField(
                      maxLines: 3,
                      decoration: InputDecoration(
                        labelText: 'Description',
                        border: OutlineInputBorder(),
                        floatingLabelBehavior: FloatingLabelBehavior.always,
                      ),
                      onSaved: (value) {
                        _description = value;
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
                        onPressed: _savePlant,
                        child: Text('Save'),
                      ),
                    ),
                  ],
                ),
              ),
            ),
          ],
        ),
      ),
    );
  }
}
