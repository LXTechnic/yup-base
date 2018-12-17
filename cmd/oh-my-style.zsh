oh-my-style() {
    local url=$1
    local file=${1##*/}
    if [[ ! -e ~/.oh-my-zsh/themes/$file ]] {
        echo "$(curl -fsSL $url)" >~/.oh-my-zsh/themes/$file
    }
}