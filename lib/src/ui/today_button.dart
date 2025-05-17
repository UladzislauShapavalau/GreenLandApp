import 'package:flutter/material.dart';
import 'package:greenland_app/src/ui/today/today_page.dart';
import 'package:greenland_app/src/config/styles/palette.dart';

class TodayButton extends StatelessWidget {
  const TodayButton({super.key, required this.onTap});

  final VoidCallback onTap;

  @override
  Widget build(BuildContext context) {
    return InkWell(
      //hoverColor: Colors.green,
      onTap: onTap,
      onHover: (value) {},
      child: Container(
        padding: const EdgeInsets.symmetric(horizontal: 16, vertical: 12),
        decoration: BoxDecoration(
          color: Palette.backgroundColor,
          borderRadius: BorderRadius.circular(12),
        ),
        child: Row(
          mainAxisSize: MainAxisSize.min,
          children: [
            Icon(
              Icons.house_outlined,
              color: Colors.black,
            ),
            const SizedBox(width: 8),
            const Text('Today'),
          ],
        ),
      ),
    );
  }
}
