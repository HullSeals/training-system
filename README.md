# Hull Seals Training System

This is the repository for the Hull Training System.

## Description

This repository houses all of the files required to build and host your own version of the Hull Seals Training System. The system is how we automate basic training for the Seals, and allow much of our training process to be automated. This helps us ensure that all Seals are trained and know how to respond to calls of any nature.

## Installation

### Requirements

- PHP 5.5+ (7.x+ Recommended)
- An SQL Server with tables to store data (Not Provided)
- A Web server software such as Apache2 or NGIX.
- A JavaScript-enabled browser.
- Some Site-Wide Assets found in our [Main Site Repository](https://gitlab.com/hull-seals-cyberseals/code/hull-seals-main-site).

### Usage

To install, download the latest [release](https://gitlab.com/hull-seals-cyberseals/code/training-system/-/releases) from our repository. Upload and extract the files to the directory or subdirectory you wish to install from, and change the information in assets/db.php to fit your server. Ensure that you have created Stored Procedures and have the appropriate tables. Due to security risks, our own example tables are not provided. Finally, navigate to the `scheduling` subdirectory and run `composer require phpmailer/phpmailer`.

### Troubleshooting

- Upon installation, be sure to replace the information in db.php to match your own details.
- Additionally, be sure to create a database and tables, and method of creating, updating, and removing data. It is encouraged to use Stored Procedures for this task.
- If you are having issues, look through the closed bug reports.
- If no issue is similar, open a new bug report. Be sure to be detailed.

## Support

The best way to receive support is through the issues section of this repository. As every setup is different, support may be unable to help you, but in general we will try when we can.
If for some reason you are unable to do so, emailing us at Code[at]hullseals[dot]space will also reach the same team.

## Roadmap

In the short term, the only major known change upcoming is the establishment of site-wide assets such as headers and footers to standardize some aspects of code, and changing the aspect view to use a center article design.

In the mid term, more courses will be added.

As always, bugfixes, speed, and stability updates are priorities as discovered, as well as general enhancements over time.

## Contributing

Interested in joining the Hull Seals Cyberseals? Read up on [the Welcome Board](https://gitlab.com/hull-seals-cyberseals/welcome-to-the-hull-seals-devops-board).

## Authors and Acknowledgements

The majority of this code was written by [David Sangrey](https://gitlab.com/Rixxan).

Many thanks to all of our [Contributors](https://gitlab.com/hull-seals-cyberseals/welcome-to-the-hull-seals-devops-board/blob/master/CONTRIBUTORS.md).

Layout design by [Wolfii Namakura](https://gitlab.com/wolfii1), implemented by [David Sangrey](https://gitlab.com/Rixxan).

## License

This project is governed under the [GNU General Public License v3.0](LICENSE) license.

## Project Status

This project is in a BETA state, with structural changes upcoming. Mind the dust - this is being updated frequently.
