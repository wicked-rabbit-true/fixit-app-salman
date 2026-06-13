import 'package:fixit_user/config.dart';
import 'package:fixit_user/models/appernce_model.dart';
import 'package:fixit_user/models/category_advertisement_model.dart';

import 'models/app_setting_model.dart';

UserModel? userModel;

PrimaryAddress? userPrimaryAddress;

String? currentAddress, street;

LatLng? position;

String zoneIds = "";

int? setPrimaryAddress;

List<Services> servicePackageList = [];

final List<CategoryModel> defaultPhaseOneCategories = [
  CategoryModel(id: 1, title: 'Handyman'),
  CategoryModel(id: 2, title: 'Carpenter'),
  CategoryModel(id: 3, title: 'Electrician'),
  CategoryModel(id: 4, title: 'Curtain fixer'),
  CategoryModel(id: 5, title: 'Plumber'),
  CategoryModel(id: 6, title: 'Cleaner'),
  CategoryModel(id: 7, title: 'Maid'),
  CategoryModel(id: 8, title: 'Shoe cleaner'),
  CategoryModel(id: 9, title: 'Wall Painter'),
  CategoryModel(id: 10, title: 'Car wash'),
  CategoryModel(id: 11, title: 'Ac technician'),
  CategoryModel(id: 12, title: 'Sofa shampoo'),
  CategoryModel(id: 13, title: 'Packing helper'),
  CategoryModel(id: 14, title: 'Furniture cleaner'),
];

List<CategoryModel> allCategoryList = List.from(defaultPhaseOneCategories);

List<CategoryModel> homeCategoryList = List.from(defaultPhaseOneCategories);
List<CategoryModel> homeHasSubCategoryList = [];
List<ServicePackageModel> homeServicePackagesList = [];
List<ProviderModel> homeProvider = [];
List<BlogModel> homeBlog = [];
List<Services> homeFeaturedService = [];
List<Services> homeServicesAdvertisements = [];
// AppearanceModel? appearanceList;
AppearanceModel? appearance;
CategoryAdvertisementModel? fetchBannerAds;
List<OnboardingModel> onboardingScreens = [];

bool? isGuest;
