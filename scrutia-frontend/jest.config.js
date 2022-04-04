module.exports = {
  preset: "@vue/cli-plugin-unit-jest/presets/typescript-and-babel",
  collectCoverage: true,
  collectCoverageFrom: [
    "src/components/**/*.{ts,vue,js}",
    "src/views/**/*.{ts,vue,js}",
    "src/*.{ts,vue,js}",
    "!src/registerServiceWorker.{ts,js}",
  ],
  coverageDirectory: "coverage",
  coverageThreshold: {
    global: {
      branches: 50,
      functions: 75,
    },
  },
};
