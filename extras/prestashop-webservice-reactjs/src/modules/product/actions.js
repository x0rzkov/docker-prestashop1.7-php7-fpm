import * as t from './actionTypes'

export const fetchFeaturesRequest = (array) => ({
  type: t.FETCH_FEATURES_REQUESTED,
  payload: {
    array: array
  }
})

export const featureSsuccess = (d) => ({
  type: t.FETCH_FEATURES_REQUESTED_SUCCEEDED,
  payload: {
    features: {
      data: d,
      fetching: false,
      error: {
        status: false,
        message: null
      }
    }
  }
})

export const featuresFailed = (err) => ({
  type: t.FETCH_FEATURES_REQUESTED_FAILED,
  payload: {
    features: {
      fetching: false,
      error: {
        status: true,
        message: err
      }
    }

  }
})

export const fetchAccessoriesRequest = (array) => ({
  type: t.FETCH_ACCESSORIES_REQUESTED,
  payload: {
    array: array
  }
})

export const accessoriesSsuccess = (d) => ({
  type: t.FETCH_ACCESSORIES_REQUESTED_SUCCEEDED,
  payload: {
    accessories: {
      data: d,
      fetching: false
    }
  }
})

export const accessoriesFailed = (err) => ({
  type: t.FETCH_ACCESSORIES_REQUESTED_FAILED,
  payload: {
    error: {
      status: true,
      message: err
    }
  }
})

export const fetchBundleRequest = (array) => ({
  type: t.FETCH_BUNDLE_REQUESTED,
  payload: {
    array: array
  }
})

export const bundleSsuccess = (d) => ({
  type: t.FETCH_BUNDLE_REQUESTED_SUCCEEDED,
  payload: {
    bundle: {
      data: d,
      fetching: false
    }
  }
})

export const bundleFailed = (err) => ({
  type: t.FETCH_BUNDLE_REQUESTED_FAILED,
  payload: {
    error: {
      status: true,
      message: err
    }
  }
})
