import 'package:fixit_user/config.dart';

class TorontoTopCategories extends StatelessWidget {
  const TorontoTopCategories({super.key});

  @override
  Widget build(BuildContext context) {
    return Consumer3<CommonApiProvider, DashboardProvider,
            CategoriesDetailsProvider>(
        builder: (context, commonApi, dash, categoryDetails, child) {
      final categories = commonApi.dashboardModel?.categories ?? [];

      return Column(
        children: [
          if (homeCategoryList.isNotEmpty)
            HeadingRowCommon(
                    title: translations!.topCategories,
                    isTextSize: true,
                    onTap: () => route.pushNamed(
                        context, routeName.categoriesListScreen))
                .paddingSymmetric(horizontal: Insets.i20),
          if (homeCategoryList.isNotEmpty) const VSpace(Sizes.s15),
          if (homeCategoryList.isNotEmpty)
            SizedBox(
              height: Sizes.s130,
              child: ListView.builder(
                physics: const BouncingScrollPhysics(),
                scrollDirection: Axis.horizontal,
                padding: const EdgeInsets.symmetric(horizontal: Sizes.s20),
                itemCount: homeCategoryList.length,
                itemBuilder: (context, index) {
                  final category = homeCategoryList[index];

                  return TopCategoriesLayout(
                    isCircle: true,
                    index: index,
                    selectedIndex: dash.topSelected,
                    data: category,
                    onTap: () {
                      categoryDetails.hasCategoryList.clear();

                      if (category.hasSubCategories != null &&
                          category.hasSubCategories!.isNotEmpty) {
                        categoryDetails.hasCategoryList.addAll(
                          category.hasSubCategories!
                              .map((subCategory) => CategoryModel(
                                    id: subCategory.id,
                                    title: subCategory.title,
                                    media: [
                                      Media(
                                        originalUrl: (subCategory.media !=
                                                    null &&
                                                subCategory.media!.isNotEmpty)
                                            ? subCategory.media![0].originalUrl
                                            : '',
                                      )
                                    ],
                                  ))
                              .toList(),
                        );
                      }

                      route.pushNamed(
                        context,
                        routeName.categoriesDetailsScreen,
                        arg: category,
                      );
                    },
                  ).padding(right: Sizes.s10);
                },
              ),
            )
        ],
      );
    });
  }
}
