#!/usr/bin/env bash
# Build VoIP Domain RPM packages from the current source tree.
#
# Usage: build-rpm.sh [version]
#
# Creates ~/rpmbuild/SOURCES/voipdomain-VERSION.tar.xz and runs rpmbuild -ba.

set -euo pipefail

ROOT="$(cd "$(dirname "${BASH_SOURCE[0]}")/../.." && pwd)"
VERSION="${1:-0.3}"
SPEC="$ROOT/Documentations/rpm/voipdomain.spec"
TARBALL="voipdomain-${VERSION}.tar.xz"
WORKDIR="$(mktemp -d)"
trap 'rm -rf "$WORKDIR"' EXIT

mkdir -p "$HOME/rpmbuild"/{BUILD,RPMS,SOURCES,SPECS,SRPMS}
STAGING="$WORKDIR/voipdomain-${VERSION}"
mkdir -p "$STAGING"

echo "Staging source tree..."
rsync -a --exclude '.git' --exclude 'node_modules' "$ROOT/" "$STAGING/"

echo "Creating $TARBALL..."
tar -C "$WORKDIR" -cJf "$HOME/rpmbuild/SOURCES/$TARBALL" "voipdomain-${VERSION}"

echo "Running rpmbuild..."
rpmbuild -ba "$SPEC" \
  -D "_sourcedir $HOME/rpmbuild/SOURCES" \
  -D "_specdir $HOME/rpmbuild/SPECS" \
  -D "_builddir $HOME/rpmbuild/BUILD" \
  -D "_srcrpmdir $HOME/rpmbuild/SRPMS" \
  -D "_rpmdir $HOME/rpmbuild/RPMS" \
  -D "version $VERSION"

echo "Done. Packages in $HOME/rpmbuild/RPMS/noarch/"
