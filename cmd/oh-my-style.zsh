oh-my-style() {
    local url=$1
    local file=${1##*/}
    echo "curl -fsSL $url"
    exit
    if [[ ! -e ~/.oh-my-zsh/themes/$file ]] {
        echo "$(curl -fsSL $url)" >~/.oh-my-zsh/themes/$file
    }
}