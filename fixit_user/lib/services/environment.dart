import 'dart:developer';
import '../config.dart';

String apiUrl = "http://162.35.160.177/api";
String paymentUrl = "Enter your payment url here";
String playstoreUrl = "Enter your playstore url here";
String userAppPlayStoreUrl = "Enter your user app playstore url here";
String googleMapKey = "Enter your google map key";
String googleSignInKey = "Enter your google signin key here";
late SharedPreferences sharedPreferences;
String local = appSettingModel!.general!.defaultLanguage!.locale!;

// Initialize SharedPreferences and Locale
Future<void> initializeAppSettings() async {
  sharedPreferences = await SharedPreferences.getInstance();
  local =
      sharedPreferences.getString('selectedLocale') ??
      appSettingModel?.general?.defaultLanguage?.locale ??
      "en";
  log("set language:: $local");
}

// Headers Token Function
Map<String, String>? headersToken(String? token) => {
  'Accept': 'application/json',
  'Content-Type': 'application/json',
  "Accept-Lang": local,
  "Authorization": "Bearer $token",
};

// Default Headers
Map<String, String>? get headers => {
  'Accept': 'application/json',
  'Content-Type': 'application/json',
  "Accept-Lang": local,
};
