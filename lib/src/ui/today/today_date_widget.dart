import 'package:flutter/material.dart';
import 'package:intl/intl.dart'; // Import intl package for date formatting

class TodayDateWidget extends StatelessWidget {
  const TodayDateWidget({super.key});

  @override
  Widget build(BuildContext context) {
    final now = DateTime.now(); // Get the current date and time
    final formattedDate =
        _formatDate(now); // Format the date using a helper function

    return Text(
      formattedDate,
      style: const TextStyle(fontSize: 20, fontWeight: FontWeight.bold),
    );
  }

  String _formatDate(DateTime date) {
    final formatter =
        DateFormat('EEEE, MMMM d, yyyy'); // Define the desired format
    return formatter.format(date); // Format the date using the formatter
  }
}
