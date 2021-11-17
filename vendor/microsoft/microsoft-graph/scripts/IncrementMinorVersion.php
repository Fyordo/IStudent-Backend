<?php
/**
 * Copyright (c) Microsoft Corporation.  All Rights Reserved.
 * Licensed under the MIT License.  See License in the project root
 * for license information.
 *
 * Bumps up the minor version in src/Core/GraphConstants.php & README based on the latest published package version on Packagist
 *
 * Assumptions:
 *  - Script is run from the repo root
 *  - Script is run on a Unix environment (affects file path separator to files)
 *  - Packagist returns tagged versions in descending order (latest release first)
*/

const CONSTANTS_FILEPATH = "./src/Core/GraphConstants.php";
const SDK_VERSION_VAR_NAME = "SDK_VERSION"; # Name of version variable in GraphConstants.php
const PACKAGIST_ENDPOINT = "https://packagist.org/packages/microsoft/microsoft-graph.json";
const CONSTANTS_README_FILEPATH = "./README.md";

function getLatestMinorPackagistVersion(string $majorVersion): string
{
    $handle = curl_init();
    curl_setopt($handle, CURLOPT_URL, PACKAGIST_ENDPOINT);
    curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($handle, CURLOPT_TIMEOUT, 100);
    curl_setopt($handle, CURLOPT_FAILONERROR, true);

    echo "Fetching latest SDK version from " . PACKAGIST_ENDPOINT . "\n";
    $response = curl_exec($handle);

    if (curl_error($handle)) {
        throw new Exception("Failed to get latest packagist version: ". curl_error($handle));
    }

    curl_close($handle);

    $responseJson = json_decode($response, true);
    if (!array_key_exists("package", $responseJson)
        || !array_key_exists("versions", $responseJson["package"])
        || empty($responseJson["package"]["versions"])) {

        throw new Exception("Unable to find versions in the packagist response JSON: ". $responseJson);
    }

    $versions = $responseJson["package"]["versions"];
    foreach ($versions as $version => $versionMetadata) {
        # Ignore branch versions
        if (!preg_match('/^dev-.*|.*-dev$/', $version)) {
            $split = explode('.', $version);
            if (!empty($split) && $split[0] === $majorVersion) {
                # Non-branch versions are returned in descending order.
                echo "Latest packagist version: {$version}\n";
                return $version;
            }
        }
    }
}

function getCurrentSdkVersion()
{
    $fileContents = file_get_contents(CONSTANTS_FILEPATH);
    if ($fileContents) {
        $pattern = '/'. SDK_VERSION_VAR_NAME . '\s+=\s+".+"/';
        $regexMatches = [];
        preg_match($pattern, $fileContents, $regexMatches);
        if ($regexMatches && $regexMatches[0]) {
            $split = explode('"', $regexMatches[0]);
            return $split[1];
        }
    }
}

function incrementMinorVersion(string $version): string
{
    $splitVersion = explode(".", $version);
    # Increment minor version
    $splitVersion[1] = strval(intval($splitVersion[1]) + 1);
    # Set patch to 0
    $splitVersion[2] = preg_replace('/[0-9]+/', "0", $splitVersion[2]);
    return implode(".", $splitVersion);
}

function updateGraphConstants(string $version)
{
    $fileContents = file_get_contents(CONSTANTS_FILEPATH);
    if ($fileContents) {
        $pattern = '/'. SDK_VERSION_VAR_NAME . '\s+=\s+".+"/';
        $replacement = SDK_VERSION_VAR_NAME . ' = "' . $version . '"';
        if (!file_put_contents(CONSTANTS_FILEPATH, preg_replace($pattern, $replacement, $fileContents))) {
            throw new Exception("Unable to find and replace SDK version variable ". SDK_VERSION_VAR_NAME);
        }
        echo "Successfully updated " . CONSTANTS_FILEPATH . "\n";
        return;
    }
    throw new Exception("Could not read GraphConstants.php at ". CONSTANTS_FILEPATH);
}

function updateReadMe(string $version)
{
    $fileContents = file_get_contents(CONSTANTS_README_FILEPATH);
    if ($fileContents) {
        $pattern = '/"microsoft\/microsoft-graph":\s+".+"/';
        $replacement = "\"microsoft/microsoft-graph\": \"^{$version}\"";
        if (!file_put_contents(CONSTANTS_README_FILEPATH, preg_replace($pattern, $replacement, $fileContents))) {
            throw new Exception("Unable to find and replace SDK version");
        }
        echo "Successfully updated README\n";
        return;
    }
    throw new Exception("Could not read README.md at " . CONSTANTS_README_FILEPATH);
}

$currentSdkVersion = getCurrentSdkVersion();
$currentMajorVersion = explode('.', $currentSdkVersion)[0];
$version = incrementMinorVersion(getLatestMinorPackagistVersion($currentMajorVersion));
echo "Version after minor increment: {$version}\n";
updateGraphConstants($version);
updateReadMe($version);
