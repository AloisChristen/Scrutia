module.exports = {
  preset: "@vue/cli-plugin-unit-jest/presets/typescript-and-babel",
  collectCoverage: true,
  collectCoverageFrom: [
    "src/components/**/*.{ts,vue,js}",
    "src/router/**/*.{ts,vue,js}",
    "src/store/**/*.{ts,vue,js}",
    "src/views/**/*.{ts,vue,js}",
    "src/*.{ts,vue,js}",
  ],
  coverageDirectory: "coverage",
  coverageThreshold: {
    global: {
      branches: 80,
      functions: 80,
      lines: 80,
      statements: -10,
    },
  },
};
