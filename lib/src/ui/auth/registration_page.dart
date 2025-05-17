import 'dart:io';

import 'package:flutter/material.dart';
import 'package:greenland_app/main.dart';
// Предполагается, что add_reminder_widget.dart не используется на этой странице,
// если это не так, раскомментируйте следующую строку:
// import 'package:greenland_app/src/ui/add_plant/add_reminder_widget.dart';

import '../../config/styles/palette.dart'; // Убедитесь, что этот путь правильный
import 'package:http/http.dart' as http;
import 'dart:convert';
import 'package:shared_preferences/shared_preferences.dart';

class AuthPage extends StatefulWidget {
  @override
  AuthPageState createState() => AuthPageState();
}

class AuthPageState extends State<AuthPage> {
  static late String username;

  final _registerFormKey = GlobalKey<FormState>();
  final _loginFormKey = GlobalKey<FormState>();
  String? _name, _registerEmail, _registerPassword;
  String? _loginEmail, _loginPassword;
  bool _isLogin = false;

  void _tryRegister() async {
    if (_registerFormKey.currentState!.validate()) {
      _registerFormKey.currentState!.save();

      //final url = 'http://localhost:8000/api/register';
      final url = 'http://10.0.2.2:8000/api/register';

      final headers = {
        HttpHeaders.contentTypeHeader: ContentType.json.toString(),
        HttpHeaders.accessControlAllowOriginHeader:
            '*' // Будьте осторожны с CORS в продакшене
      };
      final body = json.encode({
        'name': _name,
        'email': _registerEmail,
        'password': _registerPassword,
      });

      try {
        final response =
            await http.post(Uri.parse(url), headers: headers, body: body);

        if (response.statusCode == 200 || response.statusCode == 201) {
          // 201 Created часто используется для успешной регистрации
          print('Registration successful');
          // Возможно, вы захотите автоматически войти в систему или переключиться на экран входа
          setState(() {
            _isLogin = true;
          });
        } else {
          print('Registration failed: ${response.body}');
          ScaffoldMessenger.of(context).showSnackBar(
            SnackBar(content: Text('Registration failed: ${response.body}')),
          );
        }
      } catch (error) {
        print('Error: $error');
        ScaffoldMessenger.of(context).showSnackBar(
          SnackBar(content: Text('An error occurred: $error')),
        );
      }
    }
  }

  void _tryLogin() async {
    if (_loginFormKey.currentState!.validate()) {
      _loginFormKey.currentState!.save();

      //final url = 'http://localhost:8000/api/login';
      final url = 'http://10.0.2.2:8000/api/login';

      final headers = {'Content-Type': 'application/json'};
      final body = json.encode({
        'email': _loginEmail,
        'password': _loginPassword,
      });

      try {
        final response =
            await http.post(Uri.parse(url), headers: headers, body: body);

        if (response.statusCode == 200) {
          final responseData = json.decode(response.body);
          final token = responseData['token'];
          username =
              _loginEmail!; // Будьте осторожны, _loginEmail может быть null

          final prefs = await SharedPreferences.getInstance();
          await prefs.setString('auth_token', token);

          // Navigator.pushReplacement(
          //   context,
          //   MaterialPageRoute(builder: (context) => const MyHomePage()),
          // );
          // Заменяем на именованный маршрут, если он у вас настроен, или оставляем как есть.
          // Убедитесь, что MyHomePage() определен и импортирован.
          if (mounted) {
            // Проверяем, смонтирован ли виджет перед навигацией
            Navigator.pushReplacement(
              context,
              MaterialPageRoute(
                  builder: (context) =>
                      const MyHomePage()), // Убедитесь, что MyHomePage определен
            );
          }

          print('Login successful, token saved: $token');
        } else {
          print('Login failed: ${response.body}');
          ScaffoldMessenger.of(context).showSnackBar(
            SnackBar(content: Text('Login failed: ${response.body}')),
          );
        }
      } catch (error) {
        print('Error: $error');
        ScaffoldMessenger.of(context).showSnackBar(
          SnackBar(content: Text('An error occurred: $error')),
        );
      }
    }
  }

  String? _validateEmail(String? value) {
    if (value == null || value.isEmpty) {
      return 'Please enter your email';
    }
    String pattern = r'^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$';
    RegExp regex = RegExp(pattern);
    if (!regex.hasMatch(value)) {
      return 'Enter a valid email address';
    }
    return null;
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      body: Stack(
        children: [
          Container(
            decoration: BoxDecoration(
              image: DecorationImage(
                image:
                    AssetImage('assets/icons/background_pic_paporotnik2.png'),
                fit: BoxFit.cover,
              ),
            ),
          ),
          // Затемняющий слой (необязательно, для лучшей читаемости текста)
          Container(
            color: Colors.black
                .withOpacity(0.0), // Настройте прозрачность по желанию
          ),
          SingleChildScrollView(
            child: Center(
              child: Column(
                mainAxisAlignment:
                    MainAxisAlignment.center, // Центрируем контент по вертикали
                children: [
                  SizedBox(
                      height: MediaQuery.of(context).size.height *
                          0.1), // Отступ сверху
                  // Заголовок и логотип
                  Padding(
                      padding: const EdgeInsets.symmetric(vertical: 40.0),
                      child: Row(
                        mainAxisAlignment: MainAxisAlignment.center,
                        children: [
                          Image.asset(
                            'assets/icons/icon_logo.png',
                            width: 40,
                            height: 40,
                            fit: BoxFit.fill,
                            color: Colors.white,
                            colorBlendMode: BlendMode.srcIn,
                          ),
                          const SizedBox(width: 8),
                          Text(
                            'Greenland',
                            style: TextStyle(
                              fontSize: 40,
                              fontWeight: FontWeight.bold,
                              color: Colors.white,
                            ),
                          ),
                          const SizedBox(width: 28.0),
                        ],
                      )),

                  Padding(
                    padding: const EdgeInsets.symmetric(horizontal: 20.0),
                    child: Card(
                      elevation: 8,
                      color: Colors.white,
                      shape: RoundedRectangleBorder(
                        borderRadius: BorderRadius.circular(16.0),
                      ),
                      child: Padding(
                        padding:
                            const EdgeInsets.fromLTRB(20.0, 30.0, 20.0, 20.0),
                        child: Column(
                          crossAxisAlignment: CrossAxisAlignment.start,
                          mainAxisSize: MainAxisSize.min,
                          children: [
                            Text(
                              _isLogin ? 'Welcome Back!' : 'Get Started Now',
                              style: TextStyle(
                                fontSize: 22,
                                fontWeight: FontWeight.bold,
                                color: Palette.navBarColor,
                              ),
                            ),
                            if (!_isLogin)
                              Padding(
                                padding: const EdgeInsets.only(
                                    top: 8.0, bottom: 20.0),
                                child: Text(
                                  'Create your new account',
                                  style: TextStyle(
                                    fontSize: 14,
                                    color: Colors.grey[700],
                                  ),
                                ),
                              ),
                            if (_isLogin)
                              Padding(
                                padding: const EdgeInsets.only(
                                    top: 8.0, bottom: 20.0),
                                child: Text(
                                  'Login to your account',
                                  style: TextStyle(
                                    fontSize: 14,
                                    color: Colors.grey[700],
                                  ),
                                ),
                              ),
                            if (!_isLogin) _buildRegisterForm(),
                            if (_isLogin) _buildLoginForm(),
                            const SizedBox(height: 20),
                            Align(
                              alignment: Alignment.center,
                              child: TextButton(
                                onPressed: () {
                                  setState(() {
                                    _isLogin = !_isLogin;
                                  });
                                },
                                child: Text(
                                  _isLogin
                                      ? 'Create account? Sign up'
                                      : 'Have an account? Sign In',
                                  style: TextStyle(
                                    color: Palette.navBarColor,
                                    fontWeight: FontWeight.bold,
                                    fontSize: 15,
                                  ),
                                ),
                              ),
                            ),
                          ],
                        ),
                      ),
                    ),
                  ),
                  SizedBox(
                      height: MediaQuery.of(context).size.height *
                          0.1), // Отступ снизу
                ],
              ),
            ),
          ),
        ],
      ),
    );
  }

  Widget _buildRegisterForm() {
    return Form(
      key: _registerFormKey,
      child: Column(
        children: [
          TextFormField(
            decoration: InputDecoration(
              labelText: 'Full name',
              hintText: 'Enter your full name',
              prefixIcon: Icon(Icons.person_outline),
              border: OutlineInputBorder(
                borderRadius: BorderRadius.circular(12.0),
                borderSide: BorderSide.none,
              ),
              filled: true,
              fillColor: Colors.grey[200],
            ),
            validator: (value) {
              if (value == null || value.isEmpty) {
                return 'Please enter your name';
              }
              return null;
            },
            onSaved: (value) {
              _name = value;
            },
          ),
          const SizedBox(height: 16.0),
          TextFormField(
            decoration: InputDecoration(
              labelText: 'Email address',
              hintText: 'Enter your email address',
              prefixIcon: Icon(Icons.email_outlined),
              border: OutlineInputBorder(
                borderRadius: BorderRadius.circular(12.0),
                borderSide: BorderSide.none,
              ),
              filled: true,
              fillColor: Colors.grey[200],
            ),
            keyboardType: TextInputType.emailAddress,
            validator: _validateEmail,
            onSaved: (value) {
              _registerEmail = value;
            },
          ),
          const SizedBox(height: 16.0),
          TextFormField(
            decoration: InputDecoration(
              labelText: 'Password',
              hintText: 'Enter your password',
              prefixIcon: Icon(Icons.lock_outline),
              border: OutlineInputBorder(
                borderRadius: BorderRadius.circular(12.0),
                borderSide: BorderSide.none,
              ),
              filled: true,
              fillColor: Colors.grey[200],
            ),
            obscureText: true,
            validator: (value) {
              if (value == null || value.isEmpty) {
                return 'Please enter your password';
              }
              if (value.length < 6) {
                // Пример валидации длины пароля
                return 'Password must be at least 6 characters';
              }
              return null;
            },
            onSaved: (value) {
              _registerPassword = value;
            },
          ),
          // Можно добавить поле "Confirm password" для регистрации
          const SizedBox(height: 16.0),
          TextFormField(
            decoration: InputDecoration(
              labelText: 'Confirm password',
              hintText: 'Confirm your password',
              prefixIcon: Icon(Icons.lock_outline),
              border: OutlineInputBorder(
                borderRadius: BorderRadius.circular(12.0),
                borderSide: BorderSide.none,
              ),
              filled: true,
              fillColor: Colors.grey[200],
            ),
            obscureText: true,
            validator: (value) {
              if (value == null || value.isEmpty) {
                return 'Please confirm your password';
              }
              // if (value != _registerPasswordController.text) { // Если используете TextEditingController
              //   return 'Passwords do not match';
              // }
              return null;
            },
          ),
          const SizedBox(height: 25),
          SizedBox(
            width: double.infinity,
            child: ElevatedButton(
              onPressed: _tryRegister,
              style: ElevatedButton.styleFrom(
                backgroundColor: Palette.navBarColor,
                padding: EdgeInsets.symmetric(vertical: 18.0),
                textStyle: TextStyle(fontSize: 18, fontWeight: FontWeight.bold),
                foregroundColor: Colors.white,
                shape: RoundedRectangleBorder(
                  borderRadius: BorderRadius.circular(12.0),
                ),
              ),
              child: Text('Sign up'),
            ),
          ),
        ],
      ),
    );
  }

  Widget _buildLoginForm() {
    return Form(
      key: _loginFormKey,
      child: Column(
        children: [
          TextFormField(
            decoration: InputDecoration(
              labelText: 'Email address',
              hintText: 'Enter your email address',
              prefixIcon: Icon(Icons.email_outlined),
              border: OutlineInputBorder(
                borderRadius: BorderRadius.circular(12.0),
                borderSide: BorderSide.none,
              ),
              filled: true,
              fillColor: Colors.grey[200],
            ),
            keyboardType: TextInputType.emailAddress,
            validator: _validateEmail,
            onSaved: (value) {
              _loginEmail = value;
            },
          ),
          const SizedBox(height: 16.0),
          TextFormField(
            decoration: InputDecoration(
              labelText: 'Password',
              hintText: 'Enter your password',
              prefixIcon: Icon(Icons.lock_outline),
              border: OutlineInputBorder(
                borderRadius: BorderRadius.circular(12.0),
                borderSide: BorderSide.none,
              ),
              filled: true,
              fillColor: Colors.grey[200],
            ),
            obscureText: true,
            validator: (value) {
              if (value == null || value.isEmpty) {
                return 'Please enter your password';
              }
              return null;
            },
            onSaved: (value) {
              _loginPassword = value;
            },
          ),
          //Можно добавить "Forgot password?"
          Align(
            alignment: Alignment.centerRight,
            child: TextButton(
              onPressed: () {
                // Логика восстановления пароля
              },
              child: Text(
                'Forgot password?',
                style: TextStyle(color: Palette.navBarColor),
              ),
            ),
          ),
          const SizedBox(height: 25),
          SizedBox(
            width: double.infinity,
            child: ElevatedButton(
              onPressed: _tryLogin,
              style: ElevatedButton.styleFrom(
                backgroundColor: Palette.navBarColor,
                padding: EdgeInsets.symmetric(vertical: 18.0),
                textStyle: TextStyle(fontSize: 18, fontWeight: FontWeight.bold),
                foregroundColor: Colors.white,
                shape: RoundedRectangleBorder(
                  borderRadius: BorderRadius.circular(12.0),
                ),
              ),
              child: Text('Sign In'),
            ),
          ),
        ],
      ),
    );
  }
}



// Убедитесь, что MyHomePage определен, например:
/*
class MyHomePage extends StatelessWidget {
  const MyHomePage({Key? key}) : super(key: key);

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: Text('Home Page - Welcome ${AuthPageState.username}'),
      ),
      body: Center(
        child: Text('Welcome to Greenland!'),
      ),
    );
  }
}
*/