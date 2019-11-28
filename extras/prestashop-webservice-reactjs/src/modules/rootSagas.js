import { fork, all } from 'redux-saga/effects';

import category from './category';
import productlist from './productlist';
import manufacturers from './manufacturers';
import combinations from './combinations';
import currencies from './currencies';
import languages from './languages';
import product from './product';
import cms from './cms';
import stores from './stores';

export default function* rootSagas() {
  yield all([
    fork(category.saga),
    fork(productlist.saga),
    fork(combinations.saga),
    fork(manufacturers.saga),
    fork(currencies.saga),
    fork(product.saga),
    fork(languages.saga),
    fork(cms.saga),
    fork(stores.saga)
  ]);
}
