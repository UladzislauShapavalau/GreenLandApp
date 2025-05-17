// main.dart
import 'package:flutter/material.dart';
import 'package:greenland_app/src/config/styles/palette.dart'; // Убедитесь, что Palette определен и содержит navBarColor
// import 'package:greenland_app/src/ui/main_left_widget.dart'; // Больше не нужен для основного макета MyHomePage
import 'package:flutter_svg/flutter_svg.dart';
import 'package:greenland_app/src/ui/add_plant/add_plant_page.dart';
import 'package:greenland_app/src/ui/my_plants/my_plants_page.dart';
import 'package:greenland_app/src/ui/today/today_page.dart';
import 'package:greenland_app/src/ui/error_page.dart';
// import 'package:shared_preferences/shared_preferences.dart'; // Если не используется здесь, можно удалить

// Предполагается, что AuthPage импортирован из вашего файла auth_page.dart
// Если он называется registration_page.dart, то используйте этот импорт
import 'src/ui/auth/registration_page.dart'; // или import 'src/ui/auth/auth_page.dart';

// Для форматирования даты, если вы решите делать это глобально
// import 'package:intl/date_symbol_data_local.dart';

void main() async {
  // Если будете инициализировать локали
  // WidgetsFlutterBinding.ensureInitialized(); // Раскомментируйте, если main async
  // await initializeDateFormatting('en_US', null); // Пример инициализации локали
  runApp(const MyApp());
}

class MyApp extends StatelessWidget {
  const MyApp({super.key});

  @override
  Widget build(BuildContext context) {
    return MaterialApp(
      debugShowCheckedModeBanner: false,
      theme: ThemeData(
        colorScheme: ColorScheme.fromSeed(
            seedColor: Palette.navBarColor ?? Colors.green),
        useMaterial3: true,
        scaffoldBackgroundColor: Colors.white,
        appBarTheme: const AppBarTheme(
          backgroundColor: Colors.white,
          elevation: 0,
          iconTheme:
              IconThemeData(color: Colors.black87), // Цвет иконок в AppBar
          titleTextStyle: TextStyle(
              color: Colors.black,
              fontSize: 20,
              fontWeight: FontWeight.w600), // Стиль заголовка AppBar
        ),
        bottomNavigationBarTheme: BottomNavigationBarThemeData(
          selectedItemColor: Palette.navBarColor ?? Colors.green,
          unselectedItemColor: Colors.grey[600],
          showUnselectedLabels: true,
          type: BottomNavigationBarType.fixed,
        ),
      ),
      home:
          //AuthPage(), // Если вы хотите начинать с аутентификации
          const MyHomePage(), // Для теста можно сразу на MyHomePage
    );
  }
}

class MyHomePage extends StatefulWidget {
  const MyHomePage({super.key});

  @override
  State<MyHomePage> createState() => _MyHomePageState();
}

class _MyHomePageState extends State<MyHomePage> {
  int _selectedPageIndex = 0; // Отслеживает выбранную страницу для IndexedStack

  final List<Widget> _pages = [
    const TodayPage(),
    const MyPlantsPage(),
  ];

  // Эта функция будет вызываться при нажатии на элементы BottomNavigationBar
  void _onNavigate(int index) {
    if (index == 0) {
      // Today
      setState(() {
        _selectedPageIndex = 0;
      });
    } else if (index == 2) {
      // My Plants (индекс 2 в BottomNavigationBar)
      setState(() {
        _selectedPageIndex = 1; // Индекс 1 в _pages
      });
    }
    // Индекс 1 в BottomNavigationBar - это место для FloatingActionButton, он не меняет _selectedPageIndex
  }

  @override
  Widget build(BuildContext context) {
    String appBarTitle = 'Greenland'; // Заголовок по умолчанию
    if (_selectedPageIndex == 0) {
      // Для TodayPage заголовок не нужен, так как дата будет в теле страницы
      // Но если хотите, можете оставить "Greenland" или специальный заголовок
    } else if (_selectedPageIndex == 1) {
      appBarTitle = 'My Plants';
    }

    Widget? leadingWidget;
    if (_selectedPageIndex == 0) {
      // Показываем лого и название только для Today
      leadingWidget = Padding(
        padding: const EdgeInsets.only(left: 16.0),
        child: Row(
          mainAxisSize: MainAxisSize.min,
          children: [
            Image.asset('assets/icons/icon_logo.png', width: 30, height: 30),
            const SizedBox(width: 8),
            const Text('Greenland',
                style: TextStyle(fontSize: 20, fontWeight: FontWeight.w600)),
          ],
        ),
      );
    }

    return Scaffold(
      appBar: AppBar(
        // title: Text(appBarTitle), // Можно использовать динамический заголовок
        automaticallyImplyLeading:
            false, // Убираем автоматическую кнопку "назад"
        title: leadingWidget, // Лого и название слева
        titleSpacing: 0,
        actions: _selectedPageIndex == 0
            ? [
                // Иконки действий справа, только для Today
                IconButton(
                  icon: const Icon(Icons.search), // Пример иконки поиска
                  onPressed: () {
                    // Действие для поиска
                  },
                ),
                IconButton(
                  icon: const Icon(Icons.person_outline),
                  onPressed: () {
                    // Действие для профиля
                  },
                ),
                const SizedBox(width: 8), // Небольшой отступ справа
              ]
            : null, // Не показывать actions на других страницах, если не нужно
      ),
      body: IndexedStack(
        index: _selectedPageIndex,
        children: _pages,
      ),
      bottomNavigationBar: BottomNavigationBar(
        items: const <BottomNavigationBarItem>[
          BottomNavigationBarItem(
            icon: Icon(Icons.calendar_today_outlined),
            activeIcon: Icon(Icons.calendar_today),
            label: 'Today',
          ),
          BottomNavigationBarItem(
            // Пустой элемент для FAB
            icon: Icon(Icons.add,
                color: Colors.transparent), // Делаем иконку невидимой
            label: '',
          ),
          BottomNavigationBarItem(
            icon: Icon(Icons.format_list_bulleted_outlined),
            activeIcon: Icon(Icons.format_list_bulleted),
            label: 'My plants',
          ),
        ],
        // Адаптируем currentIndex: если выбрана страница MyPlants (индекс 1 в _pages),
        // то в BottomNavigationBar это соответствует элементу с индексом 2.
        currentIndex: _selectedPageIndex == 1 ? 2 : _selectedPageIndex,
        onTap: _onNavigate,
      ),
      floatingActionButtonLocation: FloatingActionButtonLocation
          .centerDocked, // Оставляем кастомную локацию или вашу с Offset(0, 30.0)
      floatingActionButton: Transform.translate(
        offset: const Offset(0, 30.0), // Ваше смещение
        child: SizedBox(
          // Оборачиваем ElevatedButton в SizedBox для точного контроля размера
          width: 80,
          height: 42,
          child: ElevatedButton(
            onPressed: () {
              print('Add Plant button pressed!');
              Navigator.push(
                context,
                MaterialPageRoute(builder: (context) => const AddPlantPage()),
              );
            },
            style: ElevatedButton.styleFrom(
              backgroundColor: Palette.navBarColor ?? Colors.green,
              foregroundColor: Colors.white, // Цвет иконки
              elevation: 2.0,
              shape: RoundedRectangleBorder(
                borderRadius: BorderRadius.circular(16.0),
              ),
              padding: EdgeInsets
                  .zero, // Убираем внутренние отступы кнопки, чтобы иконка центрировалась в SizedBox
            ),
            child: const Icon(Icons.add,
                size: 24), // <<< Иконка теперь единственный child
          ),
        ),
      ),
    );
  }
}
