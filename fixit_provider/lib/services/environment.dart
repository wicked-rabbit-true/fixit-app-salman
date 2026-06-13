//FOR DATA ENTRY
import 'dart:developer';
import 'dart:io';
import '../config.dart';

// FOR USER
String apiUrl = "http://162.35.160.177/api";
String paymentUrl = "Enter your payment url";
String googleMapKey = "Enter your Google map key";
String providerAppUrl = "Enter your provider app url";
// Global SharedPreferences and Locale
late SharedPreferences sharedPreferences;
String local = appSettingModel!.general!.defaultLanguage!.locale!;

// Initialize SharedPreferences and Locale
Future<void> initializeAppSettings() async {
  sharedPreferences = await SharedPreferences.getInstance();
  local = sharedPreferences.getString('selectedLocale') ?? "en";
  log("set language:: $local");
}

Map<String, String>? headersToken(
  String? token, {
  bool isLang = false,
  String? localLang,
}) =>
    {
      'Accept': 'application/json',
      'Content-Type': 'application/json',
      "Accept-Lang": isLang ? "$localLang" : 'en',
      "Authorization": "Bearer $token",
    };

Map<String, String>? get headers => {
      'Accept': 'application/json',
      'Content-Type': 'application/json',
      "Accept-Lang": local,
    };
