import 'package:flutter/material.dart';
//import 'package:greenland_app/src/ui/add_plants_page.dart'; // Replace with your add plants page path
import 'package:greenland_app/src/config/styles/palette.dart';

class AddPlantsButton extends StatelessWidget {
  const AddPlantsButton({super.key, required this.onTap});

  final VoidCallback onTap;

  @override
  Widget build(BuildContext context) {
    return InkWell(
      onTap: onTap,
      child: Padding(
        padding: const EdgeInsets.symmetric(horizontal: 50),
        child: DecoratedBox(
          decoration: BoxDecoration(
            color: Palette.navBarColor,
            borderRadius: BorderRadius.circular(12),
          ),
          child: SizedBox(
            height: 50,
            child: Center(
                child: Icon(
              Icons.add,
              color: Colors.white,
            )),
          ),
        ),
      ),
    );
  }
}
